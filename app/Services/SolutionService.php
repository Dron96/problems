<?php

namespace App\Services;

use App\Http\Requests\SolutionCreateRequest;
use App\Models\Solution;

class SolutionService
{
    public function store(SolutionCreateRequest $request, $problemId, $creatorId)
    {
            $input = $request->validated();
            $input['creator_id'] = $creatorId;
            $input['problem_id'] = $problemId;
            $solution = Solution::create($input);

            return $solution;
    }

    public function update(Solution $solution, $data)
    {
        $solution->fill($data);
        $solution->save();

        return $solution;
    }

    /**
     * @param Solution $solution
     * @return Solution|\Illuminate\Http\JsonResponse
     */
    public function changeInWork(Solution $solution, $inWork)
    {
        if ($solution->in_work === false & (bool)$inWork === false) {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
        $solution->in_work = boolval($inWork);
        $solution->save();

        return response()->json($solution, 200);
    }

    public function changeStatus(Solution $solution, $status)
    {
        $solution->status = $status;
        $solution->save();

        return $solution;
    }

    public function setDeadline(Solution $solution, $deadline)
    {
        $solution->deadline = $deadline;
        $solution->save();

        return $solution;
    }

    public function setExecutor(Solution $solution, $executorId)
    {
        $solution->executor_id = $executorId;
        $solution->save();

        return $solution;
    }
}
