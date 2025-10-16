<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seed database.
     */
    public function run(): void
    {
        // Data yang harus unik
        $adminData = [
            'email' => 'admin@courtplay.my.id',
        ];

        // Data yang akan diisi atau diperbarui
        $adminAttributes = [
            'first_name'        => 'CourtPlay',
            'last_name'         => 'Admin',
            'password'          => Hash::make('admin12345'), // Ubah jika perlu
            'username'         => 'admin',
            'role'              => 'admin',
            'login_token'       => Str::random(60),
            'email_verified_at' => now(),
        ];

        // 👑 Admin - Menggunakan firstOrCreate untuk mencegah duplikasi email
        User::firstOrCreate($adminData, $adminAttributes);


        $userData = [
            'email' => 'user1@courtplay.my.id',
        ];

        $userAttributes = [
            'first_name'        => 'John',
            'last_name'         => 'Doe',
            'password'          => Hash::make('user12345'), // Ubah jika perlu
            'username'         => 'johndoe',
            'role'              => 'free',
            'login_token'       => Str::random(60),
            'email_verified_at' => now(),
        ];

        // 👤 User1 (Free plan) - Menggunakan firstOrCreate
        User::firstOrCreate($userData, $userAttributes);
    }
}
