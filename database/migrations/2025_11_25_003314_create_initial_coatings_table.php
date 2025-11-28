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
        Schema::create('initial_coatings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->date('coating_date')->nullable();
            $table->string('machine_no')->nullable();
            $table->string('slurry_lot_no')->nullable();
            $table->string('loader_operator')->nullable();
            $table->string('unloader_operator')->nullable();
            $table->string('min_tb_content')->nullable();
            $table->string('sample_qty')->nullable();
            $table->string('total_magnet_weight')->nullable();
            $table->string('checker_operator')->nullable();
            $table->time('time_start')->nullable();
            $table->time('time_finished')->nullable();
            $table->json('coating_data')->nullable();
            $table->double('maximum')->nullable();
            $table->double('minimum')->nullable();
            $table->double('average')->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_coatings');
    }
};
