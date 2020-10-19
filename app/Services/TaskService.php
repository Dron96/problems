<?php

namespace App\Services;

use App\Http\Requests\TaskCreateRequest;
use App\Models\Solution;
use App\Models\Task;
use App\Models\Problem;

class TaskService
{
    public function store(TaskCreateRequest $request, $solutionId, $problemId, $creatorId)
    {
        $input = $request->validated();
        if (!(Problem::where('id', $problemId)->exists())) {
            return response()->json(['errors' => 'Такой проблемы не существует'], 404);
        }
        $countTask = Task::where('solution_id', $solutionId)->count();
        if ($countTask >= 25) {
            return response()
                ->json(['errors' => 'Задач слишком много, удалите хотя бы 1, чтобы продолжить'], 422);
        }
        $solutionExist = Task::where('solution_id', $solutionId)
            ->where('description', $request->description)
            ->whereNotNull('executor_id')
            ->where('executor_id', $request->executor_id)
            ->exists();
        if ($solutionExist === true) {
            return response()->json(['errors' => 'Такая задача уже существует с таким ответственным'], 422);
        }
        $input['creator_id'] = $creatorId;
        $input['solution_id'] = $solutionId;

        return response()->json(Task::create($input), 201);
    }

    public function isChangeable($solutionId, $problemId)
    {
        if (!(Problem::where('id', $problemId)->exists())) {
            return response()->json(['errors' => 'Такой проблемы не существует'], 404);
        }
        $solution = Solution::where('id', $solutionId)->first();
        if ($solution === null) {
            return response()->json(['errors' => 'Такого решения не существует'], 404);
        }

        return true;
    }

    public function update($solutionId, $problemId, $description, $executorId)
    {
        if ($this->isChangeable($solutionId, $problemId) === true) {
            $solutionExist = Task::where('solution_id', $solutionId)
                ->where('description', $description)
                ->whereNotNull('executor_id')
                ->where('executor_id', $executorId)
                ->exists();
            if ($solutionExist === true) {
                return response()->json(['errors' => 'Такая задача уже существует с таким ответственным'], 422);
            }
        }

        return true;
    }
}
