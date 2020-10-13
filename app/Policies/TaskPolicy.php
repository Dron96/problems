<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Solution;
use App\Models\Task;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $solution)
    {
        return $user->id === $solution->executor_id or
            $user->id === $user->group->leader_id;
    }

    public function allFunctionExceptUpdateStatus(User $user, Task $task)
    {
        return $user->id === $task->solution->executor_id or
            $user->id === $user->group->leader_id;
    }

    /**
     * Determine whether the user can change the status.
     *
     * @param  \App\User  $user
     * @param  \App\Models\Task  $task
     * @return mixed
     */
    public function changeStatus(User $user, Task $task)
    {
        return $user->id === $task->solution->executor_id or $user->id === $task->executor_id;
    }
}
