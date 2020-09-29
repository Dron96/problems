<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Models\Solution;
use App\Models\Task;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    private $taskService;

    /**
     * SolutionController constructor.
     */
    public function __construct()
    {
        $this->taskService = app(TaskService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Solution $solution
     * @return JsonResponse
     */
    public function index(Solution $solution)
    {
        return response()->json($solution->tasks()->orderBy('description')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskCreateRequest $request
     * @param Solution $solution
     * @return JsonResponse
     */
    public function store(TaskCreateRequest $request, Solution $solution)
    {
        return $this->taskService->store($request,
            $solution->id,
            $solution->problem_id,
            auth()->id());
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task)
    {
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        $problemId = $task->getProblemId();
        $validated = $request->validate(
            ['description' => 'required|min:6|max:150|regex:/^[A-Za-zА-Яа-яёЁ0-9\- ,\.:]+$/u',],
            [
                'description.required' => 'Описание задачи должно содержать не менее 6 символов',
                'description.min' => 'Описание задачи должно содержать не менее 6 символов',
                'description.max' => 'Описание задачи должно содержать не более 150 символов',
                'description.regex' => 'Для описания решения доступны только символы кириллицы, латиницы, “.”, “,”, “:”, “ “, “-”, 0-9.',
            ]);
        $response = $this->taskService->update($task->solution_id, $problemId, $validated['description'], $task->executor_id);
        if ( $response === true) {
            $task->fill($validated);
            $task->save();
        } else {
            return $response;
        }

        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Task $task)
    {
        $problemId = $task->getProblemId();
        $response = $this->taskService->isChangeable($task->solution_id, $problemId);
        if ( $response === true) {
            $task->delete();

            return response()->json(['message' => 'Задача успешно удалена'], 200);
        } else {
            return $response;
        }
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return bool|JsonResponse
     * @throws ValidationException
     */
    public function setExecutor(Request $request, Task $task)
    {
        $problemId = $task->getProblemId();
        $validated = $this->validate($request,
            ['executor_id' => 'exists:users,id'],
            ['executor_id.exists' => 'Такого ответственного не существует']);
        $response = $this->taskService->update($task->solution_id, $problemId, $task->description, $validated['executor_id']);
        if ( $response === true) {
            $task->fill($validated);
            $task->save();

            return response()->json($task, 200);
        } else {
            return $response;
        }
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return bool|JsonResponse
     * @throws ValidationException
     */
    public function setDeadline(Request $request, Task $task)
    {
        $problemId = $task->getProblemId();
        $response = $this->taskService->isChangeable($task->solution_id, $problemId);
        $validated = $this->validate($request,
            ['deadline' => 'date|after_or_equal:'.date('Y-m-d')],
            [
                'deadline.date' => 'Формат срока исполнения не верен',
                'deadline.after_or_equal' => 'Срок исполнения не может быть раньше текущей даты'
            ]);
        if ( $response === true) {
            $task->fill($validated);
            $task->save();

            return response()->json($task, 200);
        } else {
            return $response;
        }
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return bool|JsonResponse
     * @throws ValidationException
     */
    public function changeStatus(Request $request, Task $task)
    {
        $problemId = $task->getProblemId();
        $response = $this->taskService->isChangeable($task->solution_id, $problemId);
        $validated = $this->validate($request,
            ['status' => [Rule::in(['К исполнению', 'В процессе', 'Выполнено'])]],
            ['status.in' => 'Неверный статус']);
        if ( $response === true) {
            $task->fill($validated);
            $task->save();

            return response()->json($task, 200);
        } else {
            return $response;
        }
    }
}
