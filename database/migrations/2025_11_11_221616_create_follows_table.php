<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->uuid('id')->primary();  // UUID untuk primary key
            $table->jsonb('followers')->nullable(); // Menyimpan array ID followers dalam bentuk JSONB
            $table->jsonb('following')->nullable(); // Menyimpan array ID following dalam bentuk JSONB
            $table->integer('followers_count')->default(0); // Menyimpan jumlah followers
            $table->integer('following_count')->default(0); // Menyimpan jumlah following
            $table->timestamps();

            // Menambahkan foreign key constraint untuk user_id
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Balikan migrasi.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
};
