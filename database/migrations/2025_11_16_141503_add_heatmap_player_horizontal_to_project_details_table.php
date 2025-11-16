<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_details', function (Blueprint $table) {
            $table->string('link_image_heatmap_player_horizontal', 2048)
                  ->nullable()
                  ->after('link_image_heatmap_player');
        });
    }

    public function down(): void
    {
        Schema::table('project_details', function (Blueprint $table) {
            $table->dropColumn('link_image_heatmap_player_horizontal');
        });
    }
};
