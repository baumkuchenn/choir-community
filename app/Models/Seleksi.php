<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seleksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['tipe', 'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai', 'lokasi', 'pendaftaran_terakhir', 'choirs_id', 'events_id'];

    public function pendaftarSeleksis()
    {
        return $this->hasMany(PendaftarSeleksi::class, 'seleksis_id');
    }

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choirs_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
    }
}
