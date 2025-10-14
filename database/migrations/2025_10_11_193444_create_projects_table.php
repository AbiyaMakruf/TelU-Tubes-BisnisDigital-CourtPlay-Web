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
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('project_details_id')->nullable()->constrained('project_details')->onDelete('set null');
            $table->string('project_name');
            $table->boolean('is_mailed')->default(false);
            $table->timestamp('upload_date');
            $table->string('link_image_thumbnail')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
