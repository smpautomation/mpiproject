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
            // Drop the unique index
            $table->dropUnique('mass_productions_mass_prod_unique');

            // Make the column nullable
            $table->string('mass_prod')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_productions', function (Blueprint $table) {
            // Revert back to unique & not nullable
            $table->string('mass_prod')->unique()->change();
        });
    }
};
