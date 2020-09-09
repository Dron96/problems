<?php

namespace App\Repositories;

use App\Models\Solution;

class SolutionRepository
{
    public function  getAll($problemId)
    {
        $solutions1 = Solution::where('problem_id', $problemId)
            ->where('in_work', true)
            ->orderBy('name');
        $solutions2 = Solution::where('problem_id', $problemId)
            ->where('in_work', false)
            ->latest();
        $solutions = $solutions1->unionAll($solutions2);

        return $solutions;
    }

    public function getShowInWork($problemId)
    {
        $solutions = Solution::where('problem_id', $problemId)
            ->where('in_work', true)
            ->orderBy('name')
            ->get();

        return $solutions;
    }

    public function getCountSolution($problemId)
    {
        $countSolution = Solution::where('problem_id', $problemId)
            ->where('in_work', '=', true)
            ->count();

        return $countSolution;
    }
}
