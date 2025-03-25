<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seleksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai', 'lokasi', 'choirs_id'];

    public function pendaftarSeleksis()
    {
        return $this->hasMany(PendaftarSeleksi::class, 'seleksis_id');
    }
}
