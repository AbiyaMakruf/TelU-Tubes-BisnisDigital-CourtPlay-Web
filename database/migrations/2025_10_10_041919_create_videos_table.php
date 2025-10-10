<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Informasi video
            $table->string('title')->nullable();
            $table->string('video_original')->nullable();
            $table->string('video_keypoint')->nullable();
            $table->string('video_analytics')->nullable();

            // Optional: status processing
            $table->enum('status', ['uploaded', 'processing', 'completed'])->default('uploaded');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
