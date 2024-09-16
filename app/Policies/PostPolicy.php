<?php

namespace App\Policies;

use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Post $post,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $post->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Post $post,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $post->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }

}
