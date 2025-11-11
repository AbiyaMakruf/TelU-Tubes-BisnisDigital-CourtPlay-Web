<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids;

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'role',
        'profile_picture_url',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function projects()
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

     public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'follower_id');
    }

    // Menentukan relasi pengguna yang diikuti
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'user_id');
    }

    /**
     * Mengecek apakah pengguna ini mengikuti pengguna lain
     *
     * @param User $userToCheck
     * @return bool
     */
   // app/Models/User.php

    public static function isFollowing($userId, $followerId)
    {
        // Query untuk mengecek apakah pengguna $userId mengikuti $followerId
        return Follow::where('user_id', $userId)
                   ->whereRaw('CAST(following AS text) LIKE ?', ['%' . $followerId . '%'])
                   ->exists();
    }

}
