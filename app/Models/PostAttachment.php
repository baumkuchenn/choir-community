<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostAttachment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['posts_id', 'file_path', 'file_type'];

    public function posts()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }
}
