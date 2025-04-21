<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyanyi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['events_id', 'members_id', 'suara'];

    public function member()
    {
        return $this->belongsTo(Member::class, 'members_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
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
