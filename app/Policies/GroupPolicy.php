<?php

namespace App\Policies;

use App\Models\Group;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param Group $group
     * @return mixed
     */
    public function adminFunctional(User $user)
    {
        return $user->is_admin;
    }
}
