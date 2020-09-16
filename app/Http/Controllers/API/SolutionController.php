<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SolutionNameChangeRequest;
use App\Http\Requests\SolutionCreateRequest;
use App\Models\Problem;
use App\Models\Solution;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repositories\SolutionRepository;
use App\Services\SolutionService;

class SolutionController extends Controller
{
    /**
     * @var SolutionRepository
     */
    private $solutionRepository;
    private $solutionService;

    /**
     * SolutionController constructor.
     */
    public function __construct()
    {
        $this->solutionRepository = app(SolutionRepository::class);
        $this->solutionService = app(SolutionService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Problem $problem
     * @return JsonResponse|Response
     */
    public function index(Problem $problem)
    {
        $solutions = $this->solutionRepository->getAll($problem->id);

        return response()->json($solutions->get(), 200);
    }

    /**
     * Показывает решения в работе для данной проблемы
     *
     * @param Problem $problem
     * @return JsonResponse
     */
    public function showInWork(Problem $problem)
    {
        $solutions = $this->solutionRepository->getShowInWork($problem->id);

        return response()->json($solutions, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SolutionCreateRequest $request
     * @param Problem $problem
     * @return JsonResponse
     */
    public function store(SolutionCreateRequest $request, Problem $problem)
    {
        $countSolution = Solution::where('problem_id', $problem->id)->count();
        if ($countSolution < 25) {
            return response()->json($this->solutionService->store($request, $problem->id, auth()->id()), 201);
        } else {
            return response()->json(['errors' => 'Решений слишком много, удалите хотя бы 1, чтобы продолжить'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Solution $solution
     * @return JsonResponse
     */
    public function show(Solution $solution)
    {
        return response()->json($solution, 200);
    }

    /**
     * Изменяем статус "в работе"
     *
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function changeInWork(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($request->in_work == false) {
            return $this->solutionService->changeInWork($solution, $request->in_work);;
        }
        $countSolution = $this->solutionRepository->getCountSolutionInWork($solution->problem_id);
        if ($countSolution < 1) {
            return $this->solutionService->changeInWork($solution, $request->in_work);
        } else {
            return response()->json(['errors' => 'Решение в работе может быть только 1'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SolutionNameChangeRequest $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function update(SolutionNameChangeRequest $request, Solution $solution)
    {
        $validator = Validator::make($solution->toArray(),
            ['problem_id' => 'exists:problems,id,deleted_at,NULL'],
            ['exists' => 'Такой проблемы не существует']);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }

        return response()->json($this->solutionService->update($solution, $request->validated()), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Solution $solution
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        $solution->delete();

        return response()->json(['message' => 'Решение успешно удалено'], 200);
    }

    /**
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function changeStatus(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($solution->in_work) {
            $this->validate($request,
                ['status' => [Rule::in(['В процессе', 'Выполнено', null])]],
                ['status.in' => 'Неверный статус']
            );

            return response()->json($this->solutionService->changeStatus($solution, $request->status));
        } else {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
    }

    public function setDeadline(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($solution->in_work) {
            $this->validate($request,
                ['deadline' => 'required|date|after_or_equal:'.date('Y-m-d')],
                [
                    'deadline.required' => 'Поле срок исполнения не заполнено',
                    'deadline.date' => 'Неверный формат даты',
                    'deadline.after_or_equal' => 'Срок исполнения не может быть раньше текущей даты'
                ]
            );

            return response()->json($this->solutionService->setDeadline($solution, $request->deadline));
        } else {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
    }

    public function setExecutor(Request $request, Solution $solution)
    {
        $validator = $solution->hasSolutionProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        if ($solution->in_work) {
            $this->validate($request,
                ['executor_id' => 'required|exists:users,id'],
                [
                    'executor_id.required' => 'Поле ответственный не заполнено',
                    'executor_id.exists' => 'Такого пользователя не существует',
                ]
            );

            return response()->json($this->solutionService->setExecutor($solution, $request->executor_id));
        } else {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
    }
}
