<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // Import Trait ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids; // Tambahkan HasUuids di sini

    // Model ini akan secara otomatis menggunakan:
    // public $incrementing = false;
    // protected $keyType = 'string';
    // ... dan menghasilkan UUID baru saat dibuat.

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'login_token',
        'email_verified_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'login_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
