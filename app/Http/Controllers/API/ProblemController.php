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
use App\Models\Group;
use App\Models\Like;
use App\Models\Problem;
use App\Models\Solution;
use App\Services\ProblemService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProblemController extends Controller
{
    private $problemService;

    public function __construct()
    {
        $this->problemService = app(ProblemService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $problems = Problem::withCount('likes')->orderBy('name')->get()->toArray();
        foreach ($problems as &$problem) {
            $isLiked = $this->problemService->isLikedProblem($problem['id']);
            $problem['is_liked'] = $isLiked;
        }

        return response()->json($problems, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProblemCreateRequest $request
     * @return JsonResponse
     */
    public function store(ProblemCreateRequest $request)
    {
        if (auth()->user()->group_id === NULL) {
            return response()->json(['error' => 'Вы не состоите ни в одном из подразделений'], 422);
        }
        if (Problem::all()->count() >= 10000 ) {
            return response()->json(['error' => 'Список проблем переполнен'], 422);
        }
        $input = $request->validated();
        $input['creator_id'] = auth()->id();
        $problem = Problem::create($input);
        Like::create([
            'user_id' => auth()->id(),
            'problem_id' => $problem->id,
        ]);
        Solution::create([
            'name' => '',
            'problem_id' => $problem->id,
        ]);

        return response()->json($problem, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Problem $problem
     * @return JsonResponse
     */
    public function show(Problem $problem)
    {
        $data = $problem->toArray();
        $isLiked = $this->problemService->isLikedProblem($problem->id);
        $data = array_merge($data, [
            'likes_count' => $problem->likes()->count(),
            'is_liked' => $isLiked,
            ]);

        return response()->json($data, 200);
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
        $correctStatuses = ['Решена', 'Удалена'];
        if (!$this->problemService->isIncorrectStatus($problem->status, $correctStatuses)) {
            return response()->json(['error' => 'Действие возможно при любом статусе проблемы, кроме “Удалена” и “Решена”'], 422);
        }
        $problem->status = 'Удалена';
        $problem->save();
        //$problem->delete();

        return response()->json([$problem, 'message' => 'Проблема успешно удалена'], 200);
    }

    public function likeProblem(Problem $problem)
    {
        $userIds = array_map('current', $problem->likes()->select('user_id')->get()->toArray());
        if (in_array(auth()->id(), $userIds)) {
            Like::where('user_id', auth()->id())->delete();
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'problem_id' => $problem->id,
                ]);
        }

        return response()->json(['message' => 'Успешно'], 200);
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
        if ($problem->status === 'на рассмотрении') {
            $problem->status = 'в работе';
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
}
