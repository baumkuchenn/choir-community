<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanitiaPendaftarSeleksi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['users_id', 'seleksis_id', 'kehadiran', 'tipe', 'hasil_wawancara', 'lolos'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function seleksi()
    {
        return $this->belongsTo(Seleksi::class, 'seleksis_id');
    }
}
