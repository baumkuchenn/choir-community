<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ButirPenilaian extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'bobot_nilai', 'choirs_id'];

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choirs_id');
    }
}
