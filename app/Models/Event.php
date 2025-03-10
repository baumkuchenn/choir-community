<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';

    public function concert()
    {
        return $this->hasOne(Concert::class, 'events_id');
    }

    public function choirs()
    {
        return $this->belongsToMany(Choir::class, 'collabs', 'events_id', 'choirs_id')
            ->withPivot('penyelenggara');
    }
}
