<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matchmaking_searches', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id');
            $table->uuid('court_id')->nullable(); // hanya court, tanpa location

            $table->string('play_mode'); // single / double

            $table->date('play_date');
            $table->time('play_time_start');
            $table->time('play_time_end');

            $table->string('status')->default('searching');
            // searching, matched, cancelled, expired

            $table->timestamps();

            // FK
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('court_id')->references('id')->on('courts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchmaking_searches');
    }
};
