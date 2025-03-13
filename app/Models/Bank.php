<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = 'banks';

    public function concert()
    {
        return $this->hasOne(Concert::class, 'banks_id');
    }
}
