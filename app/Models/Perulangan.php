<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perulangan extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['tanggal_mulai', 'frekuensi', 'interval', 'hari', 'tipe_selesai', 'tanggal_selesai', 'jumlah', 'events_id'];
}
