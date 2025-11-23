<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matchmaking_match_players', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('matchmaking_match_id');
            $table->uuid('user_id');
            $table->uuid('from_search_id')->nullable();

            $table->unsignedSmallInteger('team'); // 1 atau 2

            $table->timestamps();

            $table->foreign('matchmaking_match_id')
                ->references('id')->on('matchmaking_matches')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('from_search_id')
                ->references('id')->on('matchmaking_searches')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchmaking_match_players');
    }
};
