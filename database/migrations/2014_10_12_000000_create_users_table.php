<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // UUID sebagai primary key
            $table->uuid('id')->primary();

            $table->string('first_name');
            $table->string('last_name')->nullable();

            // Username unik untuk login
            $table->string('username')->unique();

            $table->string('email')->unique();
            $table->string('password');
            $table->string('profile_picture_url')->nullable();

            // Role-based access
            $table->string('role')->default('free')->comment('free, starter, plus, pro, admin');

            // Token login unik
            $table->string('login_token', 80)->nullable()->unique();

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
