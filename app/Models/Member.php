<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'members';
    protected $fillable = ['choirs_id', 'users_id', 'suara', 'positions_id', 'admin'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choirs_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'positions_id');
    }

    public function penyanyis()
    {
        return $this->hasMany(Penyanyi::class, 'members_id');
    }
}
