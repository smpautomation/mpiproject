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
        Schema::create('inspection_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('thickness')->nullable();
            $table->string('material_grade')->nullable();
            $table->string('br')->nullable();
            $table->string('ihc')->nullable();
            $table->string('ihk')->nullable();
            $table->string('oven_machine_no')->nullable();
            $table->time('time_loading')->nullable();
            $table->float('temperature_1')->nullable();
            $table->date('date')->nullable();
            $table->time('time_unloading')->nullable();
            $table->float('temperature_2')->nullable();
            $table->string('shift')->nullable();
            $table->string('operator')->nullable();
            $table->string('mpi_sample')->nullable();
            $table->string('ihc_ihk')->nullable();
            $table->string('br_4-pie-la')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_data');
    }
};
