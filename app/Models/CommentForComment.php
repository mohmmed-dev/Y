<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\comment;

class CommentForComment extends Model
{
    use HasFactory;

    protected $table = 'Comments_for_comment';

    protected $fillable = [
        'user_id',
        'comment_id',
        'description',
    ];

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function comment() {
        return $this->belongsTo(Comment::class);
    }
}
