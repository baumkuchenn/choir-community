<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumTopic extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['forums_id', 'nama', 'slug', 'deskripsi'];

    public function forum()
    {
        return $this->belongsTo(Forum::class, 'forums_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'topics_id');
    }
}
