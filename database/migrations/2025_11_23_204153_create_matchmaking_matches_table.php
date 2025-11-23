<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matchmaking_matches', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('mode');   // single / double
            $table->string('status')->default('matched');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchmaking_matches');
    }
};
