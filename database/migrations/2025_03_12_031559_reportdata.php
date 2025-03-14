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
            $table->string('serial');
            $table->string('model');
            $table->string('material_code');
            $table->string('partial_number');
            $table->string('total_quantity');
            $table->string('pulse_tracer_machine_number');
            $table->date('date');
            $table->string('shift');
            $table->string('operator');
            $table->json('magnetic_property_data');
            $table->json('standard_sample_dimension_and_material_grade');
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
