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
        Schema::create('breaklot_second_coatings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod')->nullable();
            $table->string('furnace')->nullable();
            $table->string('layer')->nullable();
            $table->string('layer_code')->nullable();
            $table->json('coating_info_1stgbdp')->nullable();
            $table->json('coating_info_2ndgbdp')->nullable();
            $table->json('coating_data_1stgbdp')->nullable();
            $table->json('coating_data_2ndgbdp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breaklot_second_coatings');
    }
};
