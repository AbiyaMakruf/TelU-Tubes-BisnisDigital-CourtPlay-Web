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
            // UUID sebagai primary key
            $table->uuid('id')->primary();

            // Foreign key relasi
            $table->foreignUuid('project_id')
                  ->constrained('projects')
                  ->onDelete('cascade');

            $table->foreignUuid('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // Hardware performance fields
            $table->integer('detection_inference_time')->nullable(); // ms
            $table->integer('keypoint_inference_time')->nullable();  // ms
            $table->integer('total_inference_time')->nullable();     // ms

            // Hardware specs
            $table->string('gpu_name', 255)->nullable();
            $table->integer('vram_mb')->nullable();
            $table->string('cpu_name', 255)->nullable();
            $table->integer('cpu_threads')->nullable();
            $table->integer('ram_mb')->nullable();
            $table->string('os_info', 255)->nullable();

            // Status
            $table->boolean('is_success')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hwinfo');
    }
};
