<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'ticket_types';
    protected $fillable = ['nama', 'harga', 'jumlah', 'pembelian_terakhir', 'concerts_id', 'visibility'];

    public function concert()
    {
        return $this->belongsTo(Concert::class, 'concerts_id');
    }

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class, 'purchase_details', 'ticket_types_id', 'purchases_id')
            ->withPivot('jumlah');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'ticket_types_id');
    }

    //Cek sisa
    public function getTiketSisaAttribute()
    {
        $terjual = $this->purchases
            ->whereIn('status', ['verifikasi', 'selesai'])
            ->sum(function ($purchase) {
                return $purchase->pivot->jumlah;
            });
        return max($this->jumlah - $terjual, 0);
    }
}
