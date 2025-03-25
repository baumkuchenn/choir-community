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

    /**
     * Relationship with Seleksi model
     */
    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class, 'seleksis_id');
    }
}
