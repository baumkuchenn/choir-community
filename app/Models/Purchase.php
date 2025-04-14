<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'purchases';
    protected $fillable = ['status', 'total_tagihan', 'users_id', 'concerts_id'];

    public function concert()
    {
        return $this->belongsTo(Concert::class, 'concerts_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function ticketTypes()
    {
        return $this->belongsToMany(TicketType::class, 'purchase_details', 'purchases_id', 'ticket_types_id')
            ->withPivot('jumlah');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'purchases_id');
    }

    public function kupon()
    {
        return $this->belongsTo(Kupon::class, 'kupons_id');
    }

    public function referal()
    {
        return $this->belongsTo(Kupon::class, 'referals_id');
    }
}
