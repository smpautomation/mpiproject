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
        Schema::create('data_instructions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('tpm_data_serial')->index();
            $table->foreign('tpm_data_serial')
                ->references('serial_no')
                ->on('tpm_data')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('sample_code')->nullable();
            $table->string('corner')->nullable();
            $table->string('surface')->nullable();
            $table->string('core')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_instructions');
    }
};
