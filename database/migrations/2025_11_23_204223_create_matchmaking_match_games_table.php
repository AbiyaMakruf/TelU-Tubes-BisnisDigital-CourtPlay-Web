<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matchmaking_match_games', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('matchmaking_match_id');

            $table->unsignedInteger('game_number');
            $table->unsignedInteger('team1_score')->default(0);
            $table->unsignedInteger('team2_score')->default(0);

            $table->timestamps();

            $table->foreign('matchmaking_match_id')
                ->references('id')->on('matchmaking_matches')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matchmaking_match_games');
    }
};
