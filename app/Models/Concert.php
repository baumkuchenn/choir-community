<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Concert extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'concerts';
    protected $fillable = ['gambar', 'deskripsi', 'seating_plan', 'syarat_ketentuan', 'ebooklet', 'link_ebooklet', 'donasi', 'kupon', 'tipe_kupon', 'no_rekening', 'pemilik_rekening', 'berita_transfer', 'banks_id', 'events_id', 'status'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'banks_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'concerts_id');
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'concerts_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'concerts_id');
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class, 'concerts_id');
    }

    public function kupons()
    {
        return $this->hasMany(Kupon::class, 'concerts_id');
    }
}
