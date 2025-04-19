<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Panitia extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['events_id', 'users_id', 'jabatan_id', 'tipe'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(PanitiaJabatan::class, 'jabatan_id');
    }
}
