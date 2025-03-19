<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choir extends Model
{
    use HasFactory;
    protected $table = 'choirs';

    public function members()
    {
        return $this->hasMany(Member::class, 'choirs_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'collabs', 'choirs_id', 'events_id')
            ->withPivot('penyelenggara');
    }
}
