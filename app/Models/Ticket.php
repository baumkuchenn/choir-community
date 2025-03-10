<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'tickets';
    protected $fillable = ['number', 'barcode_code', 'barcode_image', 'invoices_id', 'ticket_types_id'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoices_id');
    }

    public function ticket_type()
    {
        return $this->belongsTo(TicketType::class, 'ticket_types_id');
    }
}
