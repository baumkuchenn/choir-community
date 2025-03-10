<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_handphone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function choirs()
    {
        return $this->belongsToMany(Choir::class, 'members', 'users_id', 'choirs_id')
            ->withPivot('admin');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'users_id');
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class, 'users_id');
    }

    public function donation()
    {
        return $this->hasOne(Donation::class, 'users_id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'users_id');
    }
}
