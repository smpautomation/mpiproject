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
        Schema::create('initial_film_pastings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('film_pasting_date')->nullable();
            $table->string('machine_no')->nullable();
            $table->double('total_magnet_weight')->nullable();
            $table->string('loader_operator')->nullable();
            $table->string('unloader_operator')->nullable();
            $table->string('checker_operator')->nullable();
            $table->string('film_coating_amount')->nullable();
            $table->time('time_start')->nullable();
            $table->time('time_finished')->nullable();
            $table->string('remarks')->nullable();
            $table->string('film_type')->nullable();
            $table->string('film_class')->nullable();
            $table->json('h_line_parameters')->nullable();
            $table->json('t_line_parameters')->nullable();
            $table->boolean('setter_sand')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_film_pastings');
    }
};
