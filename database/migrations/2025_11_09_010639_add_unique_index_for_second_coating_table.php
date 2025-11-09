<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gbdp_second_coatings', function (Blueprint $table) {
            $table->unique(['furnace', 'mass_prod', 'layer'], 'second_coating_furnace_mass_layer_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gbdp_second_coatings', function (Blueprint $table) {
            $table->dropUnique('second_coating_furnace_mass_layer_unique');
        });
    }
};
