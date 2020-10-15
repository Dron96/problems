<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\Problem;
use App\Models\Solution;
use App\Models\Task;
use App\User;
use function GuzzleHttp\Promise\all;

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
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        } else {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereNotIn('status', ['Решена', 'Удалена'])
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->deadlineFiltration($filterDeadline, $problems), 200);
    }

    public function countProblems()
    {
        $user = auth()->user();

        $userProblems = Problem::where('creator_id', auth()->id())
            ->where('status', 'На проверке заказчика')
            ->count();

        $solutionsWhereUserIsExecutorOfTasks = Task::where('executor_id', $user->id)
            ->get('solution_id')
            ->toArray();
        $problems = array_map('current', Solution::whereIn('id', $solutionsWhereUserIsExecutorOfTasks)
            ->orWhere('executor_id', $user->id)
            ->get('problem_id')
            ->toArray());
        $forExecution = Problem::whereIn('id', $problems)
            ->whereNotIn('status', ['Удалена', 'Решена', 'На рассмотрении'])
            ->count();

        $group = Group::whereId($user->group_id)->first();
        if ($group['leader_id'] === auth()->id()) {
            $groupUsersIds = $group->users()->select('id')->get()->toArray();
            $forConfirmationProblems = Problem::whereIn('creator_id', $groupUsersIds)
                ->where('status', 'На рассмотрении')
                ->count();
        if ($user->is_admin) {
            $forConfirmationProblems = Problem::where('status', 'На рассмотрении')->count();
        }

            return ['Предложенные мной' => $userProblems,
                'На рассмотрении' => $forConfirmationProblems,
                'Для исполнения' => $forExecution,
            ];
        } else {
            return ['Предложенные мной' => $userProblems,
                'Для исполнения' => $forExecution,
            ];
        }
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
                    ->orderBy('name')
                    ->with('solution')
                    ->with('groups')
                    ->get();
            } else {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->where('status', 'На рассмотрении')
                    ->orderBy('name')
                    ->with('solution')
                    ->with('groups')
                    ->get();
            }
        } elseif ($user->is_admin) {
            if ($this->isNeedFiltration($filters)) {
                $problems = Problem::where('status', 'На рассмотрении')
                    ->where($filters)
                    ->orderBy('name')
                    ->with('solution')
                    ->with('groups')
                    ->get();
            } else {
                $problems = Problem::where('status', 'На рассмотрении')
                    ->orderBy('name')
                    ->with('solution')
                    ->with('groups')
                    ->get();
            }
        }
        $this->likesCount($problems);

        return response()->json($this->deadlineFiltration($filterDeadline, $problems), 200);
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
                ->whereNotIn('status', ['Удалена', 'Решена', 'На рассмотрении'])
                ->where($filters)
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        } else {
            $problems = Problem::whereIn('id', $problems)
                ->whereNotIn('status', ['Удалена', 'Решена', 'На рассмотрении'])
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->deadlineFiltration($filterDeadline, $problems), 200);
    }

    public function problemsByGroups($filters, Group $group)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = $group->problems()
                ->whereIn('status', ['В работе', 'На проверке заказчика'])
                ->where($filters)
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        } else {
            $problems = $group->problems()
                ->whereIn('status', ['В работе', 'На проверке заказчика'])
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->deadlineFiltration($filterDeadline, $problems), 200);
    }

    public function problemsOfAllGroups($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('status', ['В работе', 'На проверке заказчика'])
                ->where($filters)
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        } else {
            $problems = Problem::whereIn('status', ['В работе', 'На проверке заказчика'])
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        }
        $this->likesCount($problems);

        return response()->json($this->deadlineFiltration($filterDeadline, $problems), 200);
    }

    public function problemsArchive($filters)
    {
        $filterDeadline = $filters['deadline'];
        unset($filters['deadline']);
        $filters = array_filter($filters);
        if (auth()->user()->is_admin) {
            if ($this->isNeedFiltration($filters)) {
                $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                    ->where($filters)
                    ->orderBy('name')
                    ->with(['solution', 'groups'])
                    ->get();
            } else {
                $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                    ->orderBy('name')
                    ->with(['solution', 'groups'])
                    ->get();
            }
            $this->likesCount($problems);

            return $this->deadlineFiltration($filterDeadline, $problems);
        }

        $usersArchive = $this->problemsUserArchive($filters);
        $groupArchive = $this->problemsGroupArchive($filters);
        $archiveForEveryone = $this->problemsArchiveForEveryone($filters);
        $archive = collect(array_merge($usersArchive, $groupArchive, $archiveForEveryone));
        return $this->deadlineFiltration($filterDeadline, $archive->unique()->sortBy('name')->values());
    }

    private function problemsArchiveForEveryone($filters)
    {
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                ->has('groups')
                ->where($filters)
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        } else {
            $problems = Problem::whereIn('status', ['Решена', 'Удалена'])
                ->has('groups')
                ->orderBy('name')
                ->with('solution')
                ->with('groups')
                ->get();
        }
        $this->likesCount($problems);

        return $problems;
    }

    private function problemsUserArchive($filters)
    {
        if ($this->isNeedFiltration($filters)) {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereIn('status', ['Решена', 'Удалена'])
                ->doesntHave('groups')
                ->where($filters)
                ->orderBy('name')
                ->with('solution')
                ->get();
        } else {
            $problems = Problem::where('creator_id', auth()->id())
                ->whereIn('status', ['Решена', 'Удалена'])
                ->doesntHave('groups')
                ->orderBy('name')
                ->with('solution')
                ->get();
        }
        $this->likesCount($problems);

        return $problems;
    }

    private function problemsGroupArchive($filters)
    {
        $user = auth()->user();
        $group = Group::whereId($user->group_id)->first();
        if (!empty($group) and $group['leader_id'] === auth()->id()) {
            $groupUsersIds = $group->users()->select('id')->get()->toArray();
        }

        if (!empty($groupUsersIds)) {
            if ($this->isNeedFiltration($filters)) {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->whereIn('status', ['Решена', 'Удалена'])
                    ->doesntHave('groups')
                    ->where($filters)
                    ->orderBy('name')
                    ->with('solution')
                    ->get();
            } else {
                $problems = Problem::whereIn('creator_id', $groupUsersIds)
                    ->whereIn('status', ['Решена', 'Удалена'])
                    ->doesntHave('groups')
                    ->orderBy('name')
                    ->with('solution')
                    ->get();
            }
            $this->likesCount($problems);

            return $problems;
        }

        return [];
    }


    private function getCurrentQuarterNumber() {
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
        $months = array_keys($mapArray, $currentQuarter);
        foreach ($months as &$month) {
            $month = date('Y') . '-' . $month;
        }
        return ['quarter' => $currentQuarter, 'months' => $months];
    }

    private function deadlineFiltration($filterDeadline, $problems)
    {
        $quarter = $this->getCurrentQuarterNumber();
        $deadline = $quarter['months'];
        $startDate = (strtotime(min($deadline) . '-01'));
        $endDate = strtotime(date("Y-m-t", strtotime(max($deadline))));
        $problems = $problems->toArray();

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
                    } else {
                        return empty($deadline);
                    }
                }));

                break;
            case null:
                $response = $problems;
                break;
        }

        return $response;
    }

    public function isNeedFiltration($filters)
    {
        return !empty($filters);
    }

    public function statisticQuantitativeIndicators()
    {
        $countProblems = Problem::where('status', '!=', 'Удалена')->count();
        $countResolved = Problem::where('status', 'Решена')->count();
        $countUnresolved = Problem::whereNotIn('status', ['Решена', 'Удалена'])->count();

        $halfYearBefore = date('Y-m-d H:i:s', strtotime("-6 months"));
        $countUnresolvedForMoreThanHalfYear = Problem::whereNotIn('status', ['Решена', 'Удалена'])
            ->where('created_at', '<=', $halfYearBefore)
            ->count();

        $now = date('Y-m-d H:i:s');
        $countUnresolvedSolutionsWithBrokenDeadline = Problem::whereNotIn('status', ['Решена', 'Удалена'])
            ->with('solution')
            ->whereHas('solution', function ($query) use ($now) {
                $query->where('deadline', '<=', $now);
            })->count();

        $countUnresolvedTasksWithBrokenDeadline = Problem::whereNotIn('status', ['Решена', 'Удалена'])
            ->with('solution')
            ->whereHas('solution', function ($query) use ($now) {
                $query->with('tasks')
                    ->whereHas('tasks', function ($query) use ($now) {
                        $query->where('deadline', '<=', $now);
                    });
            })->count();

        $countResolvedWithoutSendingToGroup = Problem::where('status', 'Решена')
            ->doesntHave('groups')
            ->count();

        return [
            '1. Всего проблем заведено в системе' => $countProblems,
            '1.1. Решено' => $countResolved,
            '1.2. Не решено' => $countUnresolved,
            '2. Количество проблем, которые не решены  уже более чем полгода' => $countUnresolvedForMoreThanHalfYear,
            '3. Текущее кол-во нерешенных проблем с нарушенным сроком исполнения решения'
                => $countUnresolvedSolutionsWithBrokenDeadline,
            '4. Текущее кол-во нерешенных проблем с нарушенным сроком исполнения задач'
                => $countUnresolvedTasksWithBrokenDeadline,
            '5. Кол-во и процент проблем, решенных на уровне сотрудник - руководитель сотрудника (процент от общего кол-ва решенных проблем)'
                => $countResolvedWithoutSendingToGroup,
            ];
    }

    public function statisticCategories()
    {
        $problems = Problem::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->get();
        $max = $problems->max('likes_count');
        if ($max < 7) {
            $mostLikedProblems = $problems->where('likes_count', $max)
                ->take(5)
                ->sortBy('name')
                ->values();
        } else {
            $mostLikedProblems = $problems->where('likes_count', $max)
                ->sortBy('name')
                ->values();
        }

        $oldestProblem = Problem::whereNotIn('status', ['Решена', 'Удалена'])
            ->orderBy('created_at')->first();

        $users = User::withCount('solutions')
            ->orderBy('solutions_count', 'desc')
            ->get();
        $max = $users->max('solutions_count');
        $busiestWorkers = $users->where('solutions_count', $max)
            ->sortBy('surname')
            ->values();

        return [
            '1. Проблема (проблемы) с наибольшим кол-во “лайков”' => $mostLikedProblems,
            '2. Проблема, которая не решается дольше всего с указанием дней с момента заведения в системе'
                => [$oldestProblem, $oldestProblem->created_at->diffInDays()],
            '3. Сотрудник (сотрудники), являющийся ответственным за решение для наибольшего кол-ва нерешенных проблем'
                => $busiestWorkers
            ];
    }

    public function statisticQuarterly()
    {
        $quarter = $this->getCurrentQuarterNumber();

        for ($i = 0; $i <= 3; $i++) {
            $startDate = date('Y-m-d', strtotime($quarter['months'][0] . '- ' . $i*3 . " month"));
            $endDate = date('Y-m-t H:i:s', strtotime($quarter['months'][2] . '23:59:59' . '- ' . $i*3 . " month"));
            $quarterProblems = Problem::whereBetween('created_at', [$startDate, $endDate])->count();
            $afterQuarterProblems = Problem::where('created_at', '<=', $endDate)->count();
            $solutionPlanned = Solution::whereBetween('deadline', [$startDate, $endDate])->count();
            $problemsQuarterlySolved = Problem::where('status', 'Решена')
                ->whereBetween('updated_at', [$startDate, $endDate])->count();
            $problemsAllSolved = Problem::where('status', 'Решена')
                ->where('updated_at', '<=', $endDate)->count();

            $quartersFindProblems[] = [
                'Квартал' => $this->getQuarterAndYear($startDate),
                'Кол-во выявленных проблем (квартал)' => $quarterProblems,
                'Кол-во выявленных проблем (всего)' => $afterQuarterProblems,
                'Кол-во проблем, планируемых к решению (квартал)' => $solutionPlanned,
                'Кол-во решенных проблем (квартал)' => $problemsQuarterlySolved,
                'Кол-во решенных проблем (всего)' => $problemsAllSolved,
                ];
        }

        return $quartersFindProblems;
    }

    private function getQuarterAndYear($date) {
        $mapArray = array(
            1 => '1',
            2 => '1',
            3 => '1',
            4 => '2',
            5 => '2',
            6 => '2',
            7 => '3',
            8 => '3',
            9 => '3',
            10 => '4',
            11 => '4',
            12 => '4'
        );
        $quarter = $mapArray[(integer)date('m', strtotime($date))];
        return $quarter . ' квартал ' . date('Y', strtotime($date)) . ' года';
    }
}
