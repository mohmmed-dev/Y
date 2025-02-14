<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Models\Group;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware() {
        return [
            new Middleware('auth:api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request ,Group $group)
    {
        // $limit =  $request->input('limit') <= 25 ? $request->input('limit') : 25;
        $posts = $group->posts;
        $posts->loadCount(['likes','comments']);
        $posts = PostResource::collection($posts);
        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Group $group)
    {
        $user = auth('api')->user();
        Gate::authorize('update-group',$group);
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => ['nullable','image']
        ]);

        if($request->hasFile('image')) {
            $pathName = str()->random(25) . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('posts',$pathName);
            $data['image'] = 'posts/' . $pathName;
        } else {
            $data['image'] = null;
        }

        // Post handling
        $post = $group->posts()->create(
            [
                'user_id' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'image' => $data['image']
            ]
        );
        $post->loadCount(['likes','comments']);
        $post = new PostResource($post);
        return $post->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, Post $post)
    {
        abort_if($group->id !== $post->group_id ,404);
        $post->loadCount(['likes','comments']);
        $post = new PostResource($post);
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Group $group, Post $post)
    {
        Gate::authorize('update', $post);
          if($request->hasFile('image')) {
            $pathName = str()->random(25) . time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->storeAs('posts',$pathName);
            $image = 'posts/' . $pathName;
            $post->update($request->except('image') + ['image' => $image]);
        } else {
            $post->update($request->all());
        }
        $post->loadCount(['likes','comments']);
        $post = new PostResource($post);
        return $post->response()->setStatusCode(200,'Post Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group,Post $post)
    {
        Gate::authorize('delete', $post);
        $post->delete();
        return response()->json(['message' => 'Post Delete Scccfully'],204);
    }
}
