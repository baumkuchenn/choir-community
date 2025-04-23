<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostConcert extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['posts_id', 'concerts_id', 'choirs_id'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'posts_id');
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class, 'concerts_id');
    }

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choirs_id');
    }
}
