<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_details', function (Blueprint $table) {
            // Mengganti $table->id() menjadi UUID
            $table->uuid('id')->primary();

            $table->text('description')->nullable();

            // Kolom-kolom link
            $table->string('link_video_original', 2048)->nullable();
            $table->string('link_video_object_detections', 2048)->nullable();
            $table->string('link_video_player_keypoints', 2048)->nullable();
            $table->string('link_video_court_keypoints', 2048)->nullable();
            $table->string('link_image_ball_droppings', 2048)->nullable();
            $table->string('link_image_heatmap_player', 2048)->nullable();
            $table->string('genai_heatmap_player_understanding', 4096)->nullable();
            $table->string('genai_ball_droppings_understanding', 4096)->nullable();

            // Kolom hitungan (INT)
            $table->unsignedInteger('forehand_count')->default(0);
            $table->unsignedInteger('backhand_count')->default(0);
            $table->unsignedInteger('serve_count')->default(0);
            $table->unsignedInteger('ready_position_count')->default(0);

            // Kolom waktu (INT)
            $table->unsignedInteger('video_duration')->nullable();
            $table->unsignedInteger('video_processing_time')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_details');
    }
};
