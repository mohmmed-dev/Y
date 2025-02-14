<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;

class RelationsController extends Controller
{
    public function UserPosts(User $user) {
        $posts = $user->posts;
        $posts->loadCount(['likes','comments']);
        return PostResource::collection($posts);
    }

    public function UserGroups(User $user) {
        return GroupResource::collection($user->groups->loadCount(['members','posts']));
    }

    public function UserMembers(User $user) {
        return GroupResource::collection($user->members->loadCount(['members','posts']));
    }

    public function GroupMembers(Group $group) {
        return UserResource::collection($group->members->loadCount(['groups','members']));
    }

    public function Posts(Request $request ,Post $post) {
        $limit =  $request->input('limit') <= 25 ? $request->input('limit') : 25;
        return PostResource::collection(Post::withCount(['likes','comments'])->paginate($limit));
    }

    public function PostLikes(Post $post) {
        return UserResource::collection($post->likes->loadCount(['groups','members']));
    }
}
