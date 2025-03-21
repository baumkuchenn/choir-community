<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'nama_singkat', 'choirs_id'];

    public function choir()
    {
        return $this->belongsTo(Choir::class, 'choirs_id');
    }

    public function positions()
    {
        return $this->hasMany(Position::class, 'divisions_id');
    }
}
