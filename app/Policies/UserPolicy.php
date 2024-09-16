<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class UserPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return True;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, ?User $model,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $model->id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.") ;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $model->id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.") ;
    }
}
