<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'feedbacks';
    protected $fillable = ['isi', 'gambar', 'concerts_id', 'users_id'];
    
    public function concert()
    {
        return $this->belongsTo(Concert::class, 'concerts_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
