<?php

namespace App\Policies;

use App\Models\Solution;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolutionPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->is_admin) {
            return true;
        }
    }

    /**
     *
     * @param  \App\User  $user
     * @param  \App\Models\Solution  $solution
     * @return mixed
     */
    public function changeName(User $user, Solution $solution)
    {
        return $user->id === $solution->executor_id or
            $user->id === $user->group->leader_id;
    }

    /**
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function changePlanTeamStatusDeadline(User $user, Solution $solution)
    {
        return $user->id === $solution->executor_id;
    }

    /**
     *
     * @param  \App\User  $user
     * @param  \App\Models\Solution  $solution
     * @return mixed
     */
    public function changeExecutor(User $user, Solution $solution)
    {
        return $user->id === $user->group->leader_id;
    }
}
