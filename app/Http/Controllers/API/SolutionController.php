<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Solution\SolutionChangePlanRequest;
use App\Http\Requests\Solution\SolutionNameChangeRequest;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\TeamForSolution;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Repositories\SolutionRepository;
use App\Services\SolutionService;
use Illuminate\Validation\ValidationException;

class SolutionController extends Controller
{
    /**
     * @var SolutionRepository
     * @var SolutionService
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
     * Получение решения для проблемы
     *
     * @param Problem $problem
     * @return JsonResponse|Response
     */
    public function index(Problem $problem)
    {
        return response()->json($problem->solution, 200);
    }

    /**
     * Получение решения по его id
     *
     * @param Solution $solution
     * @return JsonResponse
     */
    public function show(Solution $solution)
    {
        $team = TeamForSolution::where('solution_id', $solution->id)->get();
        foreach ($team as $user) {
            $users[] = $user->user;
        }
        if (!empty($users)) {
            $solution['team'] = $users;
        } else {
            $solution['team'] = null;
        }

        return response()->json($solution, 200);
    }


    /**
     * Изменить описание решения
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
     * Изменить статус у решения
     *
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changeStatus(Request $request, Solution $solution)
    {
        $validator = $solution->hasProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        $this->validate($request,
            ['status' => [Rule::in(['В процессе', 'Выполнено', null])]],
            ['status.in' => 'Неверный статус']
        );
        if ($request->status === 'Выполнено') {
            $tasks = $solution->tasks()->where('status', '!=', 'Выполнено')->count();
            if ($tasks > 0) {
                return response()->json(['errors' => 'Не все задачи выполнены'], 422);
            }
        }

        return response()->json($this->solutionService->changeStatus($solution, $request->status));
    }

    /**
     * Установить/изменить срок исполнения для решения
     *
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     * @throws ValidationException
     */
    public function setDeadline(Request $request, Solution $solution)
    {
        $validator = $solution->hasProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        $this->validate($request,
            ['deadline' => 'required|date|after_or_equal:'.date('Y-m-d')],
            [
                'deadline.required' => 'Поле срок исполнения не заполнено',
                'deadline.date' => 'Неверный формат даты',
                'deadline.after_or_equal' => 'Срок исполнения не может быть раньше текущей даты'
            ]
        );

        return response()->json($this->solutionService->setDeadline($solution, $request->deadline));
    }

    /**
     * Задать/изменить ответственного за решение
     *
     * @param Request $request
     * @param Solution $solution
     * @return JsonResponse
     * @throws ValidationException
     */
    public function setExecutor(Request $request, Solution $solution)
    {
        $validator = $solution->hasProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }
        $this->validate($request,
            ['executor_id' => 'required|exists:users,id'],
            [
                'executor_id.required' => 'Поле ответственный не заполнено',
                'executor_id.exists' => 'Такого пользователя не существует',
            ]
        );

        return response()->json($this->solutionService->setExecutor($solution, $request->executor_id));
    }

    /**
     * Задать/именить план для решения
     *
     * @param SolutionChangePlanRequest $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function setPlan(SolutionChangePlanRequest $request, Solution $solution)
    {
        $validator = $solution->hasProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }

        return response()->json($this->solutionService->setPlan($solution, $request->plan));
    }

    /**
     * Добавить пользователя в команду, работающую над решением
     *
     * @param Solution $solution
     * @param User $user
     * @return JsonResponse
     */
    public function addUserToTeam(Solution $solution, User $user)
    {
        $validator = $solution->hasProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }

        return response()->json($this->solutionService->addUserToTeam($solution->id, $user->id));
    }

    /**
     * Исключить пользователя из команды, которая работает над решением
     *
     * @param Solution $solution
     * @param User $user
     * @return JsonResponse
     */
    public function removeUserFromTeam(Solution $solution, User $user)
    {
        $validator = $solution->hasProblem($solution->id);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 404);
        }

        return response()->json($this->solutionService->removeUserFromTeam($solution->id, $user->id));
    }

    /**
     * Список пользователей, которых можно добавить в команду, ответственную за решение
     *
     * @param Solution $solution
     * @return JsonResponse
     */
    public function getPotentialExecutors(Solution $solution)
    {
        return response()->json($this->solutionRepository->getPotentialExecutors($solution));
    }
}
