<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Models\Group;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public static function middleware() {
        return [
            'auth' , new Middleware('api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request ,Group $group)
    {
        $posts = $group->posts;
        $limit =  $request->input('limit') <= 25 ? $request->input('limit') : 25;
        $posts = PostResource::collection(Post::paginate($limit));
        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Group $group)
    {
        $user = auth('api')->user();
        $group = Group::findOrFail($group->id);
        abort_if(!($user->id == $group->user_id && $group->isMember($user)),403,'You Do Not Permission To Preform This action.');
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
        $post = new PostResource(Post::create(
            [
                'user_id' => $user->id,
                'group_id' => $group->id,
                'title' => $data['title'],
                'description' => $data['description'],
            ]
        ));
        return $post->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, Post $post)
    {
        $post = Post::findOrFail($post->id);
        abort_if($group->id !== $post->group_id ,403,'This ID Not Found');
        $post = new PostResource($post);
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Group $group, Post $post)
    {
        $id = auth('api')->user();
        $post = Post::findOrFail($post->id);
        abort_if($group->id !== $post->group_id ,403,'This ID Not Found');
        Gate::authorize('update', [$post,$id]);
        $post = new PostResource($post);
        $post->update($request->all());
        return $post->response()->setStatusCode(200,'Post Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group,Post $post)
    {
        $id = auth('api')->user();
        $post = Post::findOrFail($post->id);
        abort_if($group->id !== $post->group_id ,403,'This ID Not Found');
        Gate::authorize('delete', [$post,$id]);
        $post = new PostResource($post);
        $post->delete();
        return response()->json(['message' => 'Post Delete Scccfully'],204);
    }
}
