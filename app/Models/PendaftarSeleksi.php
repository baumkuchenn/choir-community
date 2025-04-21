<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PendaftarSeleksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['users_id', 'seleksis_id', 'kehadiran', 'hasil_wawancara', 'range_suara', 'kategori_suara', 'lembar_penilaian', 'lolos'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class, 'seleksis_id');
    }

    public function nilais()
    {
        return $this->belongsToMany(ButirPenilaian::class, 'pendaftar_nilais', 'pendaftars_id', 'butirs_id')
            ->withPivot('nilai');
    }


    //If else show
    public function getKategoriSuaraLabelAttribute()
    {
        return match ($this->kategori_suara) {
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

    public function getLolosLabelAttribute()
    {
        return match ($this->lolos) {
            'belum' => 'Pending',
            'ya' => 'Diterima',
            'tidak' => 'Ditolak',
        };
    }
}
