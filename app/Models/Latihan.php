<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Latihan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['tanggal', 'jam_mulai', 'jam_selesai', 'lokasi', 'events_id'];

    public function event()
    {
        return $this->hasOne(Event::class, 'events_id');
    }
}
