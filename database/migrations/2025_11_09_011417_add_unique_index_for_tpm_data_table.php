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
        Schema::table('tpm_data', function (Blueprint $table) {
            $table->index(['furnace', 'mass_prod', 'layer_no'], 'tpm_data_furnace_mass_layer_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpm_data', function (Blueprint $table) {
            $table->dropIndex('tpm_data_furnace_mass_layer_index');
        });
    }
};
