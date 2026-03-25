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
        Schema::create('breaklot_second_heat_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('furnace')->nullable();
            $table->string('mass_prod')->nullable();
            $table->float('layer')->nullable();
            $table->string('model')->nullable();
            $table->string('lot_no')->nullable();
            $table->json('gbdp_1st')->nullable();
            $table->json('gbdp_2nd')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breaklot_second_heat_treatments');
    }
};
