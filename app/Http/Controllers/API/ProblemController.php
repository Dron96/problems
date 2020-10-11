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
use App\Http\Requests\Problem\ProblemFiltrationForExecutionRequest;
use App\Http\Requests\Problem\ProblemFiltrationRequest;
use App\Http\Requests\Problem\ProblemsArchiveFiltrationRequest;
use App\Http\Requests\Problem\UserProblemsFiltrationRequest;
use App\Models\Group;
use App\Models\Problem;
use App\Repositories\ProblemRepository;
use App\Services\ProblemService;
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
        return $this->problemService->sendToGroup($problem, $request->group_ids);
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

        return $this->problemRepository->userProblems($filters);
    }

    public function problemsForConfirmation(ProblemFiltrationForConfirmationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsForConfirmation($filters);
    }

    public function problemsForExecution(ProblemFiltrationForExecutionRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsForExecution($filters);
    }

    public function problemsByGroups(ProblemFiltrationRequest $request, Group $group)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsByGroups($filters, $group);
    }

    public function problemsOfAllGroups(ProblemFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsOfAllGroups($filters);
    }

    public function problemsArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsArchive($filters);
    }

    public function problemsUserArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsUserArchive($filters);
    }

    public function problemsGroupArchive(ProblemsArchiveFiltrationRequest $request)
    {
        $filters = $request->validated();

        return $this->problemRepository->problemsGroupArchive($filters);
    }

    public function statisticQuantitativeIndicators()
    {
        return response()->json($this->problemRepository->statisticQuantitativeIndicators(), 200);
    }

    public function statisticCategories()
    {
        return response()->json($this->problemRepository->statisticCategories(), 200);
    }

    public function statisticQuarterly()
    {
        return response()->json($this->problemRepository->statisticQuarterly(), 200);
    }

    public function countProblems()
    {
        return response()->json($this->problemRepository->countProblems(), 200);
    }

}
