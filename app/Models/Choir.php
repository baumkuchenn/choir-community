<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Choir extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'choirs';
    protected $fillable = ['nama', 'nama_singkat', 'logo', 'profil', 'tipe', 'alamat', 'deskripsi', 'jenis_rekrutmen', 'kotas_id'];

    public function members()
    {
        return $this->hasMany(Member::class, 'choirs_id');
    }

    public function divisions()
    {
        return $this->hasMany(Division::class, 'choirs_id');
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'collabs', 'choirs_id', 'events_id')
            ->withPivot('penyelenggara');
    }

    public function butirPenilaians()
    {
        return $this->hasMany(ButirPenilaian::class, 'choirs_id');
    }

    public function seleksis()
    {
        return $this->hasMany(Seleksi::class, 'choirs_id');
    }

    public function postConcerts()
    {
        return $this->hasMany(PostConcert::class, 'choirs_id');
    }
}
