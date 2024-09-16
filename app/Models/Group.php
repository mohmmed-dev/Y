<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'description',
        'image',
        'public'
    ];

    public function owner() {
        return $this->belongsTo(User::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function members() {
        return $this->belongsToMany(User::class,'members');
    }
     // public function is_follower(User $user) {
    //     return $this->followers()->where('user_id',$user->id)->where('confirmed', true)->exists();
    // }
    public function isMember(User $user) {
        return $this->members()->where('user_id',$user->id)->exists();
    }
}
