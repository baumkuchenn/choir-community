<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    use HasFactory;
    protected $table = 'purchase_details';
    protected $fillable = ['purchases_id', 'ticket_types_id', 'jumlah'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id', 'id');
    }

    public function ticket_type()
    {
        return $this->belongsTo(TicketType::class, 'ticket_types_id', 'id');
    }
}
