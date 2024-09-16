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

class CommentController extends Controller
{

    public static function middleware() {
        return [
            'auth' , new Middleware('api', except: ['index','show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        $comments = Post::findOrFail($post->id)->comments;
        $comments = CommentResource::collection($comments);
        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,Post $post)
    {
        $user = auth('api')->user();
        $post = Post::findOrFail($post->id);
        $group = Group::findOrFail($post->group_id);
        abort_if(!($user->id == $group->user_id && $group->isMember($user)),403,'You Do Not Permission To Preform This action.');
        $data = $request->validate([
            'description' => 'required'
        ]);
        $comment = new CommentResource(Comment::create(
            [
                'user_id' => $user->id,
                'post_id' => $post->id,
                'description' => $data['description']
            ]
        ));
        return $comment->response()->setStatusCode(200,'Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post,Comment $comment)
    {
        $comment = Comment::findOrFail($comment->id);
        abort_if($post->id !== $comment->post_id ,403,'This ID Not Found');
        $comment = new CommentResource($comment);
        return $comment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post,Comment $comment)
    {
        $id = auth('api')->user();
        $comment = Comment::findOrFail($comment->id);
        abort_if($post->id !== $comment->post_id ,403,'This ID Not Found');
        Gate::authorize('update', [$comment,$id]);
        $comment = new CommentResource($comment);
        $comment->update($request->all());
        return $comment->response()->setStatusCode(200,'Comment Update Scccfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post,Comment $comment)
    {
        $id = auth('api')->user();
        $comment = Comment::findOrFail($comment->id);
        abort_if($post->id !== $comment->post_id ,403,'This ID Not Found');
        Gate::authorize('update', [$comment,$id]);
        $comment = new CommentResource($comment);
        $comment->delete();
        return response()->json(['message' => 'Comment Delete Scccfully'],204);
    }
}
