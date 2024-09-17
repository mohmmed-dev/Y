<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\CommentForCommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RelationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('addMember/{user}/{group}','addMember');
        Route::get('deleteMember/{user}/{group}','deleteMember');
        Route::get('like/{user}/{post}','like');
        Route::get('unLike/{user}/{post}','unLike');
    });
    Route::apiResources([
        'users' => UserController::class,
        'groups' => GroupController::class,
        'group.posts' => PostController::class  ,
        'post.comments' => CommentController::class,
        'comment.commentForComment' => CommentForCommentController::class
        ]);
    Route::post('login',[LoginController::class,'login'])->name('login');
    Route::post('register',[UserController::class,'store'])->name('register');
    Route::controller(RelationsController::class)->group(function () {
        Route::get('users/{user}/posts','UserPosts');
        Route::get('users/{user}/groups','UserGroups');
        Route::get('users/{user}/members','UserMembers');
        Route::get('groups/{group}/members','GroupMembers');
        Route::get('posts','Posts');
        Route::get('posts/{post}/likes','PostLikes');
    });
});
