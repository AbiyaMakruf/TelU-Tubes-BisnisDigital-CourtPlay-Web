<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');                 // contoh: Court 1, Lapangan A
            $table->string('type')->nullable();     // tennis / padel / badminton / etc
            $table->string('surface')->nullable();  // hard / clay / grass / synthetic
            $table->string('city')->nullable();
            $table->string('address')->nullable();

            $table->string('status')->default('active');
            // active / inactive

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
