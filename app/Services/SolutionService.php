<?php

namespace App\Services;

use App\Models\Solution;
use App\Models\TeamForSolution;

class SolutionService
{
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

    public function addUserToTeam($solution_id, $user_id)
    {
        $data = ['user_id' => $user_id,
            'solution_id' => $solution_id];

        return TeamForSolution::create($data)->get();
    }

    public function removeUserFromTeam($solution_id, $user_id)
    {
        TeamForSolution::where('solution_id', $solution_id)
            ->where('user_id', $user_id)
            ->delete();

        return ['message' => 'Пользователь успешно исключен из команды'];
    }
}
