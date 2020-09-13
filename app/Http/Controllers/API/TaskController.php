<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskCreateRequest;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{

    private $solutionRepository;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Solution $solution)
    {
        return response()->json($solution->tasks()->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TaskCreateRequest $request, Solution $solution)
    {
        return response()->json($this->taskService->store($request,
            $solution->id,
            $solution->problem_id,
            auth()->id(),
            $solution->in_work),
            201);
    }

    /**
     * Display the specified resource.
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Task $task)
    {
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task)
    {
        $problemId = $task->getProblemId();
        //dd($problemId);
        if ($this->taskService->destroy($task->solution_id, $problemId)) {
            $task->delete();

            return response()->json(['message' => 'Задача успешно удалена'], 200);
        }
    }

    public function setExecutor(Request $request, Task $task)
    {
        //
    }

    public function setDeadline(Request $request, Task $task)
    {
        //
    }

    public function changeStatus(Request $request, Task $task)
    {
        //
    }
}
