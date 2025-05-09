<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'donations';

    public function concert()
    {
        return $this->belongsTo(Concert::class, 'concerts_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
