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
        Schema::table('tpm_boxes', function (Blueprint $table) {
            $table->unique(['tpm_data_serial', 'box_letter'], 'tpm_serial_box_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tpm_boxes', function (Blueprint $table) {
            $table->dropUnique('tpm_serial_box_unique');
        });
    }
};
