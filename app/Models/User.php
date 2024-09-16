<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Group;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentForComment;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function groups() {
        return $this->hasMany(Group::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function likes() {
        return $this->belongsToMany(Post::class,'likes')->withTimestamps();
    }

    public function commentsForComments() {
        return $this->hasMany(CommentForComment::class);
    }

    public function members() {
        return $this->belongsToMany(Group::class,'members','user_id','group_id')->withTimestamps();
    }

    public function addMember(Group $group) {
        $this->members()->attach($group);
    }

    public function deleteMember(Group $group) {
        $this->members()->detach($group);
    }

    public function unLike(Post $post) {
        $this->likes()->detach($post);
    }
    public function like(Post $post) {
        $this->likes()->attach($post);
    }


    public function is_following(User $user) {
        return $this->following()->where('group_id',$user->id)->where('confirmed',true)->exists();
    }

}
