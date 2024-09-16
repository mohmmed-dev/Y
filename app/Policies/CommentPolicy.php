<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Comment $comment,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $comment->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Comment $comment,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $comment->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }
}
