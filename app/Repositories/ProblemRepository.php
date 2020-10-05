<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\Task;

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

    public function likesCount($problems)
    {
        foreach ($problems as $problem) {
            $isLiked = $this->isLikedProblem($problem->id);
            $problem->likes_count = $problem->likes()->count();
            $problem->is_liked = $isLiked;
        }

        return $problems;
    }







    public function userProblems($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereNotIn('status', ['Решена', 'Удалена'])
                ->where($filters)
                ->with('solution')
                ->get();
        } else {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereNotIn('status', ['Решена', 'Удалена'])
                ->with('solution')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsForConfirmation($filters)
    {
        $user = auth()->user();
        $group = Group::whereId($user->group_id)->first();
        if (empty($group)) {
            return response()->json(['error' => 'Вы не состоите ни в одном из подразделений'], 422);
        }
        $groupLeader = $group['leader_id'];
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($groupLeader === auth()->id()) {
            $groupUsersIds = $group->users()->select('id')->get()->toArray();
            if ($this->isNeedFiltration($filters)) {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->where('status', 'На рассмотрении')
                    ->where($filters)
                    ->with('solution')
                    ->get();
            } else {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->where('status', 'На рассмотрении')
                    ->with('solution')
                    ->get();
            }
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsForExecution($filters)
    {
        $user = auth()->user();
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        $solutionsWhereUserIsExecutorOfTasks = Task::where('executor_id', $user->id)->get('solution_id')->toArray();
        $problems = array_map('current', Solution::whereIn('id', $solutionsWhereUserIsExecutorOfTasks)
            ->orWhere('executor_id', $user->id)
            ->get('problem_id')
            ->toArray());
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('id', $problems)
                ->where($filters)
                ->with('solution')
                ->get();
        } else {
            $problems = Problem::whereIn('id', $problems)
                ->with('solution')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($tmxbi_lhjyhis->filtration($filterDeadline, $problems), 200);
    }

    public function problemsByGroups($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = Group::with(['problems' => function ($query) use ($filters) {
                $query->whereIn('status', ['В работе', 'На проверке заказчика'])
                    ->where($filters)
                    ->with('solution');
            }])->get();
        } else {
            $problems = Group::with(['problems' => function ($query) {
                $query->whereIn('status', ['В работе', 'На проверке заказчика'])
                    ->with('solution');
            }])->get();
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsOfAllGroups($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('status', ['В работе', 'На проверке заказчика'])
                ->where($filters)
                ->with('solution')
                ->get();
        } else {
            $problems = Problem::whereIn('status', ['В работе', 'На проверке заказчика'])
                ->with('solution')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsArchive($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                ->has('groups')
                ->where($filters)
                ->with('solution')
                ->get();
        } else {
            $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                ->has('groups')
                ->with('solution')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsUserArchive($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereIn('status', ['Решена', 'Удалена'])
                ->doesntHave('groups')
                ->where($filters)
                ->with('solution')
                ->get();
        } else {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereIn('status', ['Решена', 'Удалена'])
                ->doesntHave('groups')
                ->with('solution')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }

    public function problemsGroupArchive($filters)
    {
        $user = auth()->user();
        $group = Group::whereId($user->group_id)->first();
        $groupLeader = $group['leader_id'];
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($groupLeader === auth()->id()) {
            $groupUsersIds = $group->users()->select('id')->get()->toArray();
            if ($this->isNeedFiltration($filters)) {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->whereIn('status', ['Решена', 'Удалена'])
                    ->doesntHave('groups')
                    ->where($filters)
                    ->with('solution')
                    ->get();
            } else {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->whereIn('status', ['Решена', 'Удалена'])
                    ->doesntHave('groups')
                    ->with('solution')
                    ->get();
            }
        }
        $this->likesCount($problems);

        return response()->json($this->filtration($filterDeadline, $problems), 200);
    }


    private function getCurrentQuartalNumber() {
        $mapArray = array(
            1 => '01',
            2 => '01',
            3 => '01',
            4 => '02',
            5 => '02',
            6 => '02',
            7 => '03',
            8 => '03',
            9 => '03',
            10 => '04',
            11 => '04',
            12 => '04'
        );
        $currentQuarter = $mapArray[(integer)date('m')];
        $quarterMonths = array_keys($mapArray, $currentQuarter);
        return ['quarter' => $currentQuarter, 'months' => $quarterMonths];
    }

    private function filtration($filterDeadline, $problems, $byGroups = false)
    {
        $year = date('yy');
        $quarter = $this->getCurrentQuartalNumber();

        foreach ($quarter['months'] as &$month) {
            $month = $year . '-' . $month;
        }
        $deadline = $quarter['months'];
        $startDate = (strtotime(min($deadline) . '-01'));
        $endDate = strtotime(date("Y-m-t", strtotime(max($deadline))));

        //if (!$byGroups) {
        switch ($filterDeadline) {
            case 'Текущий квартал':
                $response = array_values(array_filter($problems, function ($problem) use ($startDate, $endDate) {
                    if (!empty($problem['solution']['deadline'])){
                        $deadline = strtotime($problem['solution']['deadline']);
                        return $startDate <= $deadline and  $deadline <= $endDate;
                    }
                }));
                break;
            case 'Остальные':
                $response = array_values(array_filter($problems, function ($problem) use ($startDate, $endDate) {
                    if (!empty($problem['solution']['deadline'])){
                        $deadline = strtotime($problem['solution']['deadline']);
                        return $startDate > $deadline or $deadline > $endDate;
                    }
                }));

                break;
            case null:
                $response = $problems;
                break;
        }
//        } else {
//            switch ($filterDeadline) {
//                case 'Текущий квартал':
//                    foreach ($problems as $group) {
//                        $response = array_values(array_filter($group['problems'], function ($problem) use ($startDate, $endDate) {
//                            if (!empty($problem['problem']['solution']['deadline'])) {
//                                $deadline = strtotime($problem['problem']['solution']['deadline']);
//                                return $startDate <= $deadline and $deadline <= $endDate;
//                            }
//                        }));
//                    }
//                    break;
//                case 'Остальные':
//                    $response = $problems;
//                    foreach ($response as $group) {
//                            $problematic = array_values(array_filter($group['problems'], function ($problem) use ($startDate, $endDate) {
//                                if (!empty($problem['solution']['deadline'])) {
//                                    $deadline = strtotime($problem['solution']['deadline']);
//                                    return $startDate > $deadline or $deadline > $endDate;
//                                }
//                            }));
//                            $group['problems'] = $problematic;
//                            //print_r($problematic);
//                    }
//
//                    break;
//                case null:
//                    $response = $problems;
//                    break;
//            }
//        }


        return $response;
    }

    public function isNeedFiltration($filters)
    {
        return !empty($filters);
    }
}
