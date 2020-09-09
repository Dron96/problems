<?php

namespace App\Services;

use App\Http\Requests\SolutionCreateRequest;
use App\Models\Solution;

class SolutionService
{
    public function store(SolutionCreateRequest $request, $problem_id, $creator_id)
    {
            $input = $request->validated();
            $input['creator_id'] = $creator_id;
            $input['problem_id'] = $problem_id;
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
    public function changeInWork(Solution $solution, $in_work)
    {
        if ($solution->in_work === false & (bool)$in_work === false) {
            return response()->json(['errors' => 'Решение не в работе'], 422);
        }
        $solution->in_work = boolval($in_work);
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

    public function setExecutor(Solution $solution, $executor_id)
    {
        $solution->executor_id = $executor_id;
        $solution->save();

        return $solution;
    }
}
