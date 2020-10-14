<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Solution;
use App\User;

class SolutionRepository
{
    public function  getAll($problemId)
    {
        $solutions = Solution::where('problem_id', $problemId)
            ->orderBy('name');

        return $solutions;
    }

    public function getPotentialExecutors(Solution $solution)
    {
        $user = auth()->user();
        if ($solution->executor_id === $user->id or $user->is_admin) {
            return User::all()
                ->sortBy('father_name')
                ->sortBy('name')
                ->sortBy('surname')
                ->values();
        } elseif (!empty($user->group) and $user->group->leader_id === $user->id) {
            $usersFromGroup = $user->group->users;
            $leaders = Group::with('leader')->get();

            return $leaders->pluck('leader')
                ->merge($usersFromGroup)
                ->unique()
                ->sortBy('father_name')
                ->sortBy('name')
                ->sortBy('surname')
                ->values();
        } else {
            return null;
        }
    }
}
