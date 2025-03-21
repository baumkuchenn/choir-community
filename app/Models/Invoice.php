<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'invoices';
    protected $fillable = ['kode', 'purchases_id'];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchases_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'invoices_id');
    }
}
