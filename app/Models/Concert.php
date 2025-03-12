<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;
    protected $table = 'concerts';
    protected $fillable = ['gambar', 'deskripsi', 'seating_plan', 'syarat_ketentuan', 'ebooklet', 'link_ebooklet', 'donasi', 'kupon', 'tipe_kupon', 'no_rekening', 'pemilik_rekening', 'banks_id', 'events_id'];

    public function event()
    {
        return $this->belongsTo(Event::class, 'events_id');
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
}
