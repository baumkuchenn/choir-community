<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'events';
    protected $fillable = ['nama', 'parent_kegiatan', 'jenis_kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai', 'lokasi', 'peran', 'panitia_eksternal', 'metode_rekrut_panitia', 'visibility', 'sub_kegiatan_id'];

    public function concert()
    {
        return $this->hasOne(Concert::class, 'events_id');
    }

    public function choirs()
    {
        return $this->belongsToMany(Choir::class, 'collabs', 'events_id', 'choirs_id')
            ->withPivot('penyelenggara')
            ->withTimestamps();
    }

    public function penyanyis()
    {
        return $this->hasMany(Penyanyi::class, 'events_id');
    }
}
