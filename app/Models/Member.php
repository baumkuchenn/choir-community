<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $table = 'members';
    protected $fillable = ['choirs_id', 'users_id', 'admin'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choirs_id');
    }
}
