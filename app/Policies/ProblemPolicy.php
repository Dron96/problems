<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Problem;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProblemPolicy
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
     * @return mixed
     */
    public function changeOwnModeratingProblem(User $user, Problem $problem)
    {
        if ($problem->status === 'На рассмотрении') {
            return $user->id === $problem->creator_id;
        }
    }

    /**
     *
     * @param  \App\User  $user
     * @param  \App\Models\Problem  $problem
     * @return mixed
     */
    public function changeOwnProblem(User $user, Problem $problem)
    {
        return $user->id === $problem->creator_id;
    }

    /**
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function changeUrgencyImportanceProgress(User $user, Problem $problem)
    {
        return $user->id === $problem->solution->executor_id;
    }

    /**
     *
     * @param  \App\User  $user
     * @param  \App\Models\Problem  $problem
     * @return mixed
     */
    public function changeExperienceResultSendForConfirmationSendToGroup(User $user, Problem $problem)
    {
        $creator = User::find($problem->creator_id);
        $leader_id = $creator->group->leader_id;

        return $user->id === $problem->solution->executor_id or
            $user->id === $leader_id;
    }
}
