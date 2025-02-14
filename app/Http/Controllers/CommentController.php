<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;


class CommentController extends Controller implements HasMiddleware
{

    public static function middleware() {
        return [
            new Middleware('auth:api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        $comments = $post->comments;
        $comments->loadCount('replies');
        $comments = CommentResource::collection($comments);
        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Post $post)
    {
        $user = auth('api')->user();
        $group = $post->group;
        Gate::authorize('update-group',$group);
        $data = $request->validate([
            'description' => 'required'
        ]);
        $comment = $post->comments()->create(
            [
                'user_id' => $user->id,
                'description' => $data['description']
            ]
        );
        $comment->loadCount('replies');
        $comment = new CommentResource($comment);
        return $comment->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post,Comment $comment)
    {
        abort_if($post->id !== $comment->post_id ,404,'This ID Not Found');
        $comment->loadCount('replies');
        $comment = new CommentResource($comment);
        return $comment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post,Comment $comment)
    {
        abort_if($post->id !== $comment->post_id ,404,'This ID Not Found');
        Gate::authorize('update', $comment);
        $comment->loadCount('replies');
        $comment = new CommentResource($comment);
        $comment->update($request->all());
        return $comment->response()->setStatusCode(200,'Comment Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post,Comment $comment)
    {
        abort_if($post->id !== $comment->post_id ,404,'This ID Not Found');
        Gate::authorize('delete', $comment);
        $comment->delete();
        return response()->json(['message' => 'Comment Delete Scccfully'],204);
    }
}
