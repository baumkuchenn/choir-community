<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostReaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['posts_id', 'users_id', 'tipe'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
