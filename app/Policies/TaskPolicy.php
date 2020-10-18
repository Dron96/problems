<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @param $solution
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
     * @param User $user
     * @param Task $task
     * @return mixed
     */
    public function changeStatus(User $user, Task $task)
    {
        return $user->id === $task->executor_id;
    }
}
