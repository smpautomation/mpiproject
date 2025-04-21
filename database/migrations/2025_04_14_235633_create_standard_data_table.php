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
        Schema::create('standard_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('tpm_data_serial')->index();
            $table->foreign('tpm_data_serial')
                ->references('serial_no')
                ->on('tpm_data')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('brcpk_ucl')->nullable();
            $table->integer('brcpk_lcl')->nullable();
            $table->integer('lsl_corner')->nullable();
            $table->integer('lsl_surface')->nullable();
            $table->integer('lsl_core')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standard_data');
    }
};
