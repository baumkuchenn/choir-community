<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concert extends Model
{
    use HasFactory;
    protected $table = 'concerts';

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
