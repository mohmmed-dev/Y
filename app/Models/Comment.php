<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\CommentForComment;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'description',
    ];

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function post() {
        return $this->belongsTo(post::class);
    }

    public function comments() {
        return $this->hasMany(CommentForComment::class);
    }
}
