<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

use function PHPUnit\Framework\isNull;

class GroupPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function view(User $user,Group $group) {
        return $user->id === $group->user_id ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }
    public function update(User $user, Group $group)
    {
        return $user->id === $group->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }
}
