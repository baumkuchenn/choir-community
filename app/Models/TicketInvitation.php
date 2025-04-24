<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketInvitation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nama', 'no_handphone', 'email', 'tickets_id'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'invitations_id');
    }
}
