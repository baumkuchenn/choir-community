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
}
