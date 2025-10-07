<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tpm_data', function (Blueprint $table) {
            // Rename furnace_id -> mass_prod
            $table->renameColumn('furnace_id', 'mass_prod');
        });

        Schema::table('tpm_data', function (Blueprint $table) {
            // Change to string + nullable
            $table->string('mass_prod')->nullable()->change();

            // Make layer_no nullable
            $table->unsignedBigInteger('layer_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tpm_data', function (Blueprint $table) {
            // Revert layer_no back to not nullable
            $table->unsignedBigInteger('layer_no')->nullable(false)->change();

            // Revert mass_prod back to furnace_id as unsignedBigInteger not nullable
            $table->unsignedBigInteger('mass_prod')->change();
            $table->renameColumn('mass_prod', 'furnace_id');
        });
    }
};
