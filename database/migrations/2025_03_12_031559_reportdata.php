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
        Schema::create('report_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('tpm_data_id');
            $table->string('model')->nullable();
            $table->string('material_code')->nullable();
            $table->string('partial_number')->nullable();
            $table->string('total_quantity')->nullable();
            $table->string('pulse_tracer_machine_number')->nullable();
            $table->date('date')->nullable();
            $table->string('shift')->nullable();
            $table->string('operator')->nullable();
            $table->json('magnetic_property_data')->nullable();
            $table->json('standard_sample_dimension_and_material_grade')->nullable();
            $table->json('br_cpk')->nullable();
            $table->foreign('tpm_data_id')->references('id')->on('report_data')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_data');
    }
};
