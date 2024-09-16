<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CommentForComment;
use App\Models\Comment;
use App\Http\Resources\CommentForCommentResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class CommentForCommentController extends Controller
{

    public static function middleware() {
        return [
            'auth' , new Middleware('api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Comment $comment)
    {
        $comments = Comment::findOrFail($comment->id)->comments;
        $comments = CommentForCommentResource::collection($comments);
        return $comments;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Comment $comment)
    {
        $user = auth('api')->user();
        $comment = Comment::findOrFail($comment->id);
        $post = Post::findOrFail($comment->post_id);
        $group = Group::findOrFail($post->group_id);
        abort_if(!($user->id == $group->user_id && $group->isMember($user)),403,'You Do Not Permission To Preform This action.');
        $post = Post::findOrFail($comment->post_id);
        $data = $request->validate([
            'description' => 'required'
        ]);
        $CommentForComment = new CommentForCommentResource(CommentForComment::create(
            [
                'user_id' => $user->id,
                'comment_id' => $comment->id,
                'description' => $data['description']
            ]
        ));
        return $CommentForComment->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment,CommentForComment $commentForComment)
    {
        $CommentForComment = CommentForComment::findOrFail($commentForComment->id);
        abort_if($comment->id !== $CommentForComment->comment_id ,403,'This ID Not Found');
        $CommentForComment = new CommentForCommentResource($CommentForComment);
        return $CommentForComment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment ,CommentForComment $commentForComment)
    {
        $id = auth('api')->user();
        $CommentForComment = CommentForComment::findOrFail($commentForComment->id);
        abort_if($comment->id !== $CommentForComment->comment_id ,403,'This ID Not Found');
        Gate::authorize('update',[$CommentForComment,$id]);
        $CommentForComment = new CommentForCommentResource($CommentForComment);
        $CommentForComment->update($request->all());
        return $CommentForComment->response()->setStatusCode(200,'CommentForComment Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Comment $comment ,CommentForComment $commentForComment)
    {
        $id = auth('api')->user();
        $CommentForComment = CommentForComment::findOrFail($commentForComment->id);
        abort_if($comment->id !== $CommentForComment->comment_id ,403,'This ID Not Found');
        Gate::authorize('delete',[$CommentForComment,$id]);
        $CommentForComment = new CommentForCommentResource($CommentForComment);
        $CommentForComment->delete();
        return response()->json(['message' => 'CommentForComment Delete Scccfully'],204);
    }
}
