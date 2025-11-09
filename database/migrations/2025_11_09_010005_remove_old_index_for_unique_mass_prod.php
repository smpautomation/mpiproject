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
        Schema::table('mass_productions', function (Blueprint $table) {
            $table->dropIndex('mass_productions_mass_prod_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_productions', function (Blueprint $table) {
            $table->index('mass_prod');
        });
    }
};
