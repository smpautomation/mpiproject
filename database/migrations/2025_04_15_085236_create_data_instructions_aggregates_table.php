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
        Schema::create('data_instructions_aggregates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('tpm_data_serial')->index();
            $table->foreign('tpm_data_serial')
                ->references('serial_no')
                ->on('tpm_data')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('average')->nullable();
            $table->integer('minimum')->nullable();
            $table->integer('maximum')->nullable();
            $table->float('cpk')->nullable();
            $table->string('judgement')->nullable();
            $table->string('judgement_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_instructions_aggregates');
    }
};
