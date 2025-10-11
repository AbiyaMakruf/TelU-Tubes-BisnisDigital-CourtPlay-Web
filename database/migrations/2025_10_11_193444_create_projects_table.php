<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            // Primary Key: UUID
            $table->uuid('id')->primary();

            // Foreign Keys: UUID
            // Menggunakan foreignUuid() untuk memastikan tipe data sesuai dengan tabel 'users'
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');

            // Menggunakan foreignUuid() untuk memastikan tipe data sesuai dengan tabel 'project_details'
            // Jika Anda ingin relasi bersifat nullable, tambahkan ->nullable() sebelum ->constrained()
            $table->foreignUuid('project_details_id')->nullable()->constrained('project_details')->onDelete('set null');

            $table->string('project_name');
            $table->timestamp('upload_date');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_mailed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
