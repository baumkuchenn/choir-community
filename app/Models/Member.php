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

    public function kupons()
    {
        return $this->hasMany(Kupon::class, 'members_id');
    }


    //If else show
    public function getSuaraLabelAttribute()
    {
        return match ($this->suara) {
            'sopran_1' => 'Sopran 1',
            'sopran_2' => 'Sopran 2',
            'alto_1' => 'Alto 1',
            'alto_2' => 'Alto 2',
            'tenor_1' => 'Tenor 1',
            'tenor_2' => 'Tenor 2',
            'bass_1' => 'Bass 1',
            'bass_2' => 'Bass 2',
        };
    }
}
