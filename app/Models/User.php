<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'login_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'login_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔹 Helper untuk role checking
    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
