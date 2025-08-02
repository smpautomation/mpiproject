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
        Schema::create('mass_productions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod')->unique();
            $table->string('furnace')->nullable();
            $table->string('batch_cycle_no')->nullable();
            $table->string('machine_no')->nullable();
            $table->string('cycle_no')->nullable();
            $table->unsignedBigInteger('pattern_no')->nullable();
            $table->string('cycle_pattern')->nullable();
            $table->string('current_pattern')->nullable();
            $table->date('date_start')->nullable();
            $table->time('time_start')->nullable();
            $table->date('date_finished')->nullable();
            $table->time('time_finished')->nullable();
            $table->string('loader')->nullable();
            $table->string('unloader')->nullable();
            $table->string('box_condition')->nullable();
            $table->string('box_cover')->nullable();
            $table->string('box_arrangement')->nullable();
            $table->string('encoded_by')->nullable();
            $table->string('remarks1')->nullable();
            $table->string('remarks2')->nullable();
            $table->string('remarks3')->nullable();
            $table->json('layer_1')->nullable();
            $table->json('layer_2')->nullable();
            $table->json('layer_3')->nullable();
            $table->json('layer_4')->nullable();
            $table->json('layer_5')->nullable();
            $table->json('layer_6')->nullable();
            $table->json('layer_7')->nullable();
            $table->json('layer_8')->nullable();
            $table->json('layer_9')->nullable();
            $table->json('layer_9_5')->nullable();
            $table->float('grand_total_weight')->nullable();
            $table->unsignedBigInteger('grand_total_quantity')->nullable();
            $table->unsignedBigInteger('layer_1_serial')->nullable();
            $table->unsignedBigInteger('layer_2_serial')->nullable();
            $table->unsignedBigInteger('layer_3_serial')->nullable();
            $table->unsignedBigInteger('layer_4_serial')->nullable();
            $table->unsignedBigInteger('layer_5_serial')->nullable();
            $table->unsignedBigInteger('layer_6_serial')->nullable();
            $table->unsignedBigInteger('layer_7_serial')->nullable();
            $table->unsignedBigInteger('layer_8_serial')->nullable();
            $table->unsignedBigInteger('layer_9_serial')->nullable();
            $table->unsignedBigInteger('layer_9_5_serial')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mass_productions');
    }
};
