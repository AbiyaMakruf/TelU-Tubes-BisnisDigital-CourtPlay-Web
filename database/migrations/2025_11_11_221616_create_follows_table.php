<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->json('followers')->nullable();
            $table->json('following')->nullable();
            $table->timestamps();
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
}
