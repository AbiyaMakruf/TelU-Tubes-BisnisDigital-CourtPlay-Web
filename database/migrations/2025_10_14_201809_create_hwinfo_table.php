<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hwinfo', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('project_id')
                ->constrained('projects')
                ->onDelete('cascade');

            $table->foreignUuid('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('gpu_name', 255)->nullable();
            $table->integer('vram_mb')->nullable();
            $table->string('cpu_name', 255)->nullable();
            $table->integer('cpu_threads')->nullable();
            $table->integer('ram_mb')->nullable();
            $table->string('os_info', 255)->nullable();

            $table->float('read_video_metadata')->nullable();
            $table->float('scene_detect')->nullable();
            $table->float('ball_detection')->nullable();
            $table->float('court_detection')->nullable();
            $table->float('player_detection')->nullable();
            $table->float('bounce_detection')->nullable();
            $table->float('combine_render')->nullable();
            $table->float('render_heatmap_player')->nullable();
            $table->float('render_heatmap_ball')->nullable();
            $table->float('player_keypoint')->nullable();
            $table->boolean('is_success')->default(false);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hwinfo');
    }
};
