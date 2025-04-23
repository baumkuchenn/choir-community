<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['forums_id', 'topics_id', 'creator_id', 'isi', 'tipe', 'parent_id'];

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forums_id');
    }

    public function topic()
    {
        return $this->belongsTo(ForumTopic::class, 'topics_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function reply()
    {
        return $this->belongsTo(Post::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'parent_id');
    }

    public function postConcerts()
    {
        return $this->hasOne(PostConcert::class, 'posts_id');
    }

    public function postReactions()
    {
        return $this->hasMany(PostReaction::class, 'posts_id');
    }

    public function userReaction()
    {
        return $this->hasOne(PostReaction::class, 'posts_id')
            ->where('users_id', auth()->id());
    }

    public function postAttachments()
    {
        return $this->hasMany(PostAttachment::class, 'posts_id');
    }


    //hitung jumlah reply
    public function allRepliesCount()
    {
        return $this->replies->sum(function ($reply) {
            return 1 + $reply->allRepliesCount();
        });
    }
}
