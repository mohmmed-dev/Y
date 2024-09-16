<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\PostResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CommentForCommentResource;
use App\Http\Resources\CommentResource;

class RelationsController extends Controller
{
    public function UserPosts(User $user) {
        return PostResource::collection($user->posts);
    }

    public function UserGroups(User $user) {
        return GroupResource::collection($user->groups);
    }

    public function UserMembers(User $user) {
        return GroupResource::collection($user->members);
    }

    public function Posts(Request $request ,Post $post) {
        $limit =  $request->input('limit') <= 25 ? $request->input('limit') : 25;
        return PostResource::collection(Post::paginate($limit));
    }

    public function GroupMembers(Group $group) {
        return UserResource::collection($group->members);
    }

    public function PostLikes(Post $post) {
        return UserResource::collection($post->likes);
    }
}
