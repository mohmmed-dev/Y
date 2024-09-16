<?php

namespace App\Policies;

use App\Models\CommentForComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentForCommentPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, CommentForComment $commentForComment,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $commentForComment->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, CommentForComment $commentForComment,string $id)
    {
        $auth = json_decode($id);
        return $auth->id === $commentForComment->user_id  ? Response::allow() : Response::deny("You Do Not Permission To Preform This action.");
    }

}
