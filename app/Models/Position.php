<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'divisions_id', 'akses_member', 'akses_event', 'akses_roles', 'akses_eticket', 'akses_forum'];

    public function members()
    {
        return $this->hasMany(Member::class, 'positions_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'divisions_id');
    }
}
