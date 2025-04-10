<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanitiaDivisi extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'nama_singkat', 'events_id'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
    }

    public function jabatans()
    {
        return $this->hasMany(PanitiaJabatan::class, 'divisi_id');
    }
}
