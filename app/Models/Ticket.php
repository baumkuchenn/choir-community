<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'tickets';
    protected $fillable = ['number', 'barcode_code', 'barcode_image', 'invoices_id', 'invitations_id', 'ticket_types_id', 'check_in', 'waktu_check_in'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoices_id');
    }

    public function ticketInvitation()
    {
        return $this->belongsTo(TicketInvitation::class, 'invitations_id');
    }

    public function ticket_type()
    {
        return $this->belongsTo(TicketType::class, 'ticket_types_id');
    }
}
