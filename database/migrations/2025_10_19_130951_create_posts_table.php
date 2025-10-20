<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');                 // judul
            $table->string('slug')->unique();        // untuk URL
            $table->string('excerpt', 280)->nullable(); // ringkasan singkat
            $table->text('content')->nullable();     // isi artikel (HTML/markdown yang sudah dirender)
            $table->string('cover_url', 512)->nullable(); // URL gambar cover
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();

            $table->index(['is_published','published_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('posts');
    }
};
