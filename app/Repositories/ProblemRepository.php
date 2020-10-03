<?php

namespace App\Repositories;

use App\Models\Problem;
use App\Models\Solution;
use App\User;

class ProblemRepository
{
    public function getProblems()
    {
        $problems = Problem::withCount('likes')->orderBy('name')->get()->toArray();
        foreach ($problems as &$problem) {
            $isLiked = $this->isLikedProblem($problem['id']);
            $problem['is_liked'] = $isLiked;
        }

        return $problems;
    }

    public function isLikedProblem($id)
    {
        $problem = Problem::where('id', $id)->with('likes')->first()->toArray();
        $userIds = array_column($problem["likes"], 'user_id');

        return in_array(auth()->id(), $userIds);
    }

    public function showProblem(Problem $problem)
    {
        $data = $problem->toArray();
        $isLiked = $this->isLikedProblem($problem->id);
        $data = array_merge($data, [
            'likes_count' => $problem->likes()->count(),
            'is_liked' => $isLiked,
        ]);

        return $data;
    }
}
