<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanitiaJabatan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'divisi_id', 'akses_event', 'akses_eticket'];

    public function panitias()
    {
        return $this->hasMany(Panitia::class, 'jabatan_id');
    }

    public function divisi()
    {
        return $this->belongsTo(PanitiaDivisi::class, 'divisi_id');
    }
}
