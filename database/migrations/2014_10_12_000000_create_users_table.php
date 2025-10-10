<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Identitas dasar
            $table->string('firstname');
            $table->string('lastname')->nullable();

            // Akun & autentikasi
            $table->string('email')->unique();
            $table->string('password');

            // 🔹 Role user (default: 'user')
            $table->string('role')->default('user')->comment('pro, plus, free');

            // 🔹 Token untuk API login
            $table->string('login_token', 80)->nullable()->unique();

            // Laravel built-in fields
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
