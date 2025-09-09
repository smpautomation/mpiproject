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
        Schema::create('gbdp_second_coatings', function (Blueprint $table) {
            $table->id();
            $table->string('mass_prod')->nullable();
            $table->float('layer')->nullable();
            $table->json('coating_info_1stgbdp');
            $table->json('coating_info_2ndgbdp');
            $table->json('coating_data_1stgbdp');
            $table->json('coating_data_2ndgbdp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gbdp_second_coatings');
    }
};
