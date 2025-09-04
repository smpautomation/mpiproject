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
        Schema::create('second_g_b_d_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('mass_prod')->unique();
            $table->string('furnace')->nullable();
            $table->string('cycle_no')->nullable();
            $table->unsignedBigInteger('pattern_no')->nullable();
            $table->string('cycle_pattern')->nullable();
            $table->string('current_pattern')->nullable();
            $table->date('date_start')->nullable();
            $table->time('time_start')->nullable();
            $table->date('date_finished')->nullable();
            $table->time('time_finished')->nullable();
            $table->string('ht_remarks')->nullable();
            $table->date('date')->nullable();
            $table->string('slurry_lot_no')->nullable();
            $table->string('min_tb_content')->nullable();
            $table->string('total_magnet_weight')->nullable();
            $table->string('coating_remarks')->nullable();
            $table->json('coating_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_g_b_d_p_s');
    }
};
