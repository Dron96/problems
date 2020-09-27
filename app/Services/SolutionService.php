<?php

namespace App\Services;

use App\Http\Requests\Solution\SolutionCreateRequest;
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

    public function setPlan(Solution $solution, $plan)
    {
        $solution->plan = $plan;
        $solution->save();

        return $solution;
    }

    public function setTeam(Solution $solution, $team)
    {
        $solution->team = $team;
        $solution->save();

        return $solution;
    }
}
