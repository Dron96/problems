<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Problem\ProblemChangeDescriptionRequest;
use App\Http\Requests\Problem\ProblemChangeExperienceRequest;
use App\Http\Requests\Problem\ProblemChangeImportanceRequest;
use App\Http\Requests\Problem\ProblemChangePossibleSolutionRequest;
use App\Http\Requests\Problem\ProblemChangeProgressRequest;
use App\Http\Requests\Problem\ProblemChangeResultRequest;
use App\Http\Requests\Problem\ProblemChangeUrgencyRequest;
use App\Http\Requests\Problem\ProblemCreateRequest;
use App\Http\Requests\Problem\ProblemFiltrationForConfirmationRequest;
use App\Http\Requests\Problem\ProblemFiltrationRequest;
use App\Http\Requests\Problem\ProblemsArchiveFiltrationRequest;
use App\Http\Requests\Problem\UserProblemsFiltrationRequest;
use App\Models\Group;
use App\Models\Like;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\Task;
use App\Repositories\ProblemRepository;
use App\Services\ProblemService;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    private $problemService;
    private $problemRepository;

    public function __construct()
    {
        $this->problemRepository = app(ProblemRepository::class);
        $this->problemService = app(ProblemService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json($this->problemRepository->getProblems(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProblemCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProblemCreateRequest $request)
    {
        return $this->problemService->store($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param Problem $problem
     * @return JsonResponse
     */
    public function show(Problem $problem)
    {
        return response()->json($this->problemRepository->showProblem($problem), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProblemCreateRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function update(ProblemCreateRequest $request, Problem $problem)
    {
        $correctStatuses = ['На рассмотрении'];
        $error = 'Действие возможно только при статусе проблемы “на рассмотрении”';

        return $this->problemService->updateWithStatusCheck($problem, $request->validated(), $correctStatuses, $error);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Problem $problem
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Problem $problem)
    {
        return $this->problemService->delete($problem);
    }

    public function likeProblem(Problem $problem)
    {
        return $this->problemService->likeProblem($problem);
    }

    public function sendToGroup(Request $request, Problem $problem)
    {
        $groupIds = $request->group_ids;
        $groups = Group::find($groupIds);
        if ($request->group_ids == NULL) {
            return response()->json(['error' => 'Выберите хотя бы одно подразделение для отправки проблемы']);
        }
        if (sizeof($groups) !== sizeof($request->group_ids)) {
            return response()->json(['error' => 'Выбрано не существующее подразделение'], 422);
        }
        $problem->groups()->detach();
        $problem->groups()->attach($groups);
        if ($problem->status === 'На рассмотрении') {
            $problem->status = 'В работе';
            $problem->save();
        }

        return response()->json($problem->groups, 200);
    }

    public function setExperience(ProblemChangeExperienceRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    public function setResult(ProblemChangeResultRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    public function setPossibleSolution(ProblemChangePossibleSolutionRequest $request, Problem $problem)
    {
        $correctStatuses = ['На рассмотрении'];
        $error = 'Действие возможно только при статусе проблемы “на рассмотрении”';

        return $this->problemService->updateWithStatusCheck($problem, $request->validated(), $correctStatuses, $error);
    }

    public function setDescription(ProblemChangeDescriptionRequest $request, Problem $problem)
    {
        $correctStatuses = ['На рассмотрении'];
        $error = 'Действие возможно только при статусе проблемы “на рассмотрении”';

        return $this->problemService->updateWithStatusCheck($problem, $request->validated(), $correctStatuses, $error);
    }

    public function setImportance(ProblemChangeImportanceRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    public function setProgress(ProblemChangeProgressRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    public function setUrgency(ProblemChangeUrgencyRequest $request, Problem $problem)
    {
        return response()->json($this->problemService->update($problem, $request->validated()), 200);
    }

    public function sendForConfirmation(Problem $problem)
    {
        return $this->problemService->sendForConfirmation($problem, $problem->creator_id, $problem->solution);
    }

    public function rejectSolution(Problem $problem)
    {
        $data = ['status' => 'На рассмотрении'];
        $correctStatuses = ['На проверке заказчика'];
        $error = 'Действие возможно только при статусе проблемы “На проверке заказчика”';

        return $this->problemService->updateWithStatusCheck($problem, $data, $correctStatuses, $error);
    }

    public function confirmSolution(Problem $problem)
    {
        $data = ['status' => 'Решена'];
        $correctStatuses = ['На проверке заказчика'];
        $error = 'Действие возможно только при статусе проблемы “На проверке заказчика”';

        return $this->problemService->updateWithStatusCheck($problem, $data, $correctStatuses, $error);
    }

    public function userProblems(UserProblemsFiltrationRequest $request)
    {
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];

        unset($filters['deadline']);
        $filters = array_filter($filters);

        if ($this->problemService->isNeedFiltration($filters)) {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereNotIn('status', ['Решена', 'Удалена'])
                ->where($filters)
                ->with('solution')
                ->get()
                ->toArray();
        } else {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereNotIn('status', ['Решена', 'Удалена'])
                ->with('solution')
                ->get()
                ->toArray();
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsForConfirmation(ProblemFiltrationForConfirmationRequest $request)
    {
        $user = auth()->user();
        $group = Group::whereId($user->group_id)->first();
        $groupLeader = $group['leader_id'];
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($groupLeader === auth()->id()) {
            $groupUsersIds = $group->users()->select('id')->get()->toArray();
            if ($this->problemService->isNeedFiltration($filters)) {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->where('status', 'На рассмотрении')
                    ->where($filters)
                    ->with('solution')
                    ->get()
                    ->toArray();
            } else {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->where('status', 'На рассмотрении')
                    ->with('solution')
                    ->get()
                    ->toArray();
            }
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsForExecution(ProblemFiltrationRequest $request)
    {
        $user = auth()->user();
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        $solutionsWhereUserIsExecutorOfTasks = Task::where('executor_id', $user->id)->get('solution_id')->toArray();
        $problems = array_map('current', Solution::whereIn('id', $solutionsWhereUserIsExecutorOfTasks)
            ->orWhere('executor_id', $user->id)
            ->get('problem_id')
            ->toArray());
        if ($this->problemService->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('id', $problems)
                ->where($filters)
                ->with('solution')
                ->get()
                ->toArray();
        } else {
            $problems = Problem::whereIn('id', $problems)
                ->with('solution')
                ->get()
                ->toArray();
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsByGroups(ProblemFiltrationRequest $request)
    {
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);

        if ($this->problemService->isNeedFiltration($filters)) {
            $problems = Group::with(['problems' => function ($query) use ($filters) {
                $query->whereIn('status', ['В работе', 'На проверке заказчика'])
                    ->where($filters)
                    ->with('solution');
            }])
                ->get()
                ->toArray();
        } else {
            $problems = Group::with(['problems' => function ($query) {
                $query->whereIn('status', ['В работе', 'На проверке заказчика'])
                    ->with('solution');
            }])

                ->get()
                ->toArray();
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);;
    }

    public function problemsOfAllGroups(ProblemFiltrationRequest $request)
    {
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->problemService->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('status', ['В работе', 'На проверке заказчика'])
                ->where($filters)
                ->with('solution')
                ->get()
                ->toArray();
        } else {
            $problems = Problem::whereIn('status', ['В работе', 'На проверке заказчика'])
                ->with('solution')
                ->get()
                ->toArray();
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);;
    }

    public function problemsArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->problemService->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                ->has('groups')
                ->where($filters)
                ->with('solution')
                ->get()
                ->toArray();
        } else {
            $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                ->has('groups')
                ->with('solution')
                ->get()
                ->toArray();
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsUserArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->problemService->isNeedFiltration($filters)) {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereIn('status', ['Решена', 'Удалена'])
                ->doesntHave('groups')
                ->where($filters)
                ->with('solution')
                ->get()
                ->toArray();
        } else {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereIn('status', ['Решена', 'Удалена'])
                ->doesntHave('groups')
                ->with('solution')
                ->get()
                ->toArray();
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsGroupArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $user = auth()->user();
        $group = Group::whereId($user->group_id)->first();
        $groupLeader = $group['leader_id'];
        $filters = $request->validated();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($groupLeader === auth()->id()) {
            $groupUsersIds = $group->users()->select('id')->get()->toArray();
            if ($this->problemService->isNeedFiltration($filters)) {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->whereIn('status', ['Решена', 'Удалена'])
                    ->doesntHave('groups')
                    ->where($filters)
                    ->with('solution')
                    ->get()
                    ->toArray();
            } else {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->whereIn('status', ['Решена', 'Удалена'])
                    ->doesntHave('groups')
                    ->with('solution')
                    ->get()
                    ->toArray();
            }
        }

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }


    private function getCurrentQuartalNumber() {
        $mapArray = array(
            1 => '01',
            2 => '01',
            3 => '01',
            4 => '02',
            5 => '02',
            6 => '02',
            7 => '03',
            8 => '03',
            9 => '03',
            10 => '04',
            11 => '04',
            12 => '04'
        );
        $currentQuarter = $mapArray[(integer)date('m')];
        $quarterMonths = array_keys($mapArray, $currentQuarter);
        return ['quarter' => $currentQuarter, 'months' => $quarterMonths];
    }

    private function filtration($filterDeadline, $problems, $byGroups = false)
    {
        $year = date('yy');
        $quarter = $this->getCurrentQuartalNumber();

        foreach ($quarter['months'] as &$month) {
            $month = $year . '-' . $month;
        }
        $deadline = $quarter['months'];
        $startDate = (strtotime(min($deadline) . '-01'));
        $endDate = strtotime(date("Y-m-t", strtotime(max($deadline))));

        //if (!$byGroups) {
            switch ($filterDeadline) {
                case 'Текущий квартал':
                    $response = array_values(array_filter($problems, function ($problem) use ($startDate, $endDate) {
                        if (!empty($problem['solution']['deadline'])){
                            $deadline = strtotime($problem['solution']['deadline']);
                            return $startDate <= $deadline and  $deadline <= $endDate;
                        }
                    }));
                    break;
                case 'Остальные':
                    $response = array_values(array_filter($problems, function ($problem) use ($startDate, $endDate) {
                        if (!empty($problem['solution']['deadline'])){
                            $deadline = strtotime($problem['solution']['deadline']);
                            return $startDate > $deadline or $deadline > $endDate;
                        }
                    }));

                    break;
                case null:
                    $response = $problems;
                    break;
            }
//        } else {
//            switch ($filterDeadline) {
//                case 'Текущий квартал':
//                    foreach ($problems as $group) {
//                        $response = array_values(array_filter($group['problems'], function ($problem) use ($startDate, $endDate) {
//                            if (!empty($problem['problem']['solution']['deadline'])) {
//                                $deadline = strtotime($problem['problem']['solution']['deadline']);
//                                return $startDate <= $deadline and $deadline <= $endDate;
//                            }
//                        }));
//                    }
//                    break;
//                case 'Остальные':
//                    $response = $problems;
//                    foreach ($response as $group) {
//                            $problematic = array_values(array_filter($group['problems'], function ($problem) use ($startDate, $endDate) {
//                                if (!empty($problem['solution']['deadline'])) {
//                                    $deadline = strtotime($problem['solution']['deadline']);
//                                    return $startDate > $deadline or $deadline > $endDate;
//                                }
//                            }));
//                            $group['problems'] = $problematic;
//                            //print_r($problematic);
//                    }
//
//                    break;
//                case null:
//                    $response = $problems;
//                    break;
//            }
//        }


        return $response;
    }
}
