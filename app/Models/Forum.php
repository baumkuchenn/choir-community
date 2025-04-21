<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forum extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'foto_profil', 'foto_banner', 'slug', 'deskripsi', 'visibility', 'creator_id'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->hasMany(ForumMember::class, 'forums_id');
    }

    public function topics()
    {
        return $this->hasMany(ForumTopic::class, 'forums_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'forums_id');
    }


    //If else show
    public function getVisibilityLabelAttribute()
    {
        return $this->visibility === 'private' ? 'Privat' : 'Publik';
    }
}
