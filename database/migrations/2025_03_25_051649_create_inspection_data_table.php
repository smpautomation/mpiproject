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
            $table->string('model');
            $table->float('lenght');
            $table->float('width');
            $table->float('thickness');
            $table->string('material_grade');
            $table->string('br');
            $table->string('ihc');
            $table->string('ihk');
            $table->string('oven_machine_no');
            $table->time('time_loading');
            $table->float('temperature_1');
            $table->date('date');
            $table->time('time_unloading');
            $table->float('temperature_2');
            $table->string('shift');
            $table->string('operator');
            $table->string('mpi_sample');
            $table->string('ihc_ihk');
            $table->string('br_4-pie-la');
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
