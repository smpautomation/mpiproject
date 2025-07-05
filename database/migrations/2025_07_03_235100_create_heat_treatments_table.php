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
        Schema::create('heat_treatments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('serial')->unique();
            $table->string('furnace_machine')->nullable();
            $table->string('cycle_no')->nullable();
            $table->string('batch_cycle_no')->nullable();
            $table->string('pattern_no')->nullable();
            $table->date('date_start')->nullable();
            $table->time('time_start')->nullable();
            $table->string('loader')->nullable();
            $table->date('date_finish')->nullable();
            $table->time('time_finish')->nullable();
            $table->string('unloader')->nullable();
            $table->string('cycle_pattern')->nullable();
            $table->string('current_pattern')->nullable();
            $table->json('magnet_box_location')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heat_treatments');
    }
};
