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
        Schema::create('mie_gx_data_instructions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('tpm_data_serial')->index();
            $table->foreign('tpm_data_serial')
                ->references('serial_no')
                ->on('tpm_data')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('gx_br')->nullable();
            $table->integer('gx_ihc')->nullable();
            $table->integer('gx_ihk')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mie_gx_data_instructions');
    }
};
