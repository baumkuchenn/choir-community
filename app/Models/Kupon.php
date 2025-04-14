<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kupon extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['tipe', 'kode', 'potongan', 'jumlah', 'waktu_expired', 'members_id', 'concerts_id'];

    public function concert()
    {
        return $this->belongsTo(Concert::class, 'concerts_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'members_id');
    }

    public function usedAsKupon()
    {
        return $this->hasMany(Kupon::class, 'kupons_id');
    }

    public function usedAsReferal()
    {
        return $this->hasMany(Kupon::class, 'referals_id');
    }
}
