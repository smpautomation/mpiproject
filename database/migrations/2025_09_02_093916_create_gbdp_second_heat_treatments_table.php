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
        Schema::create('gbdp_second_heat_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('mass_prod')->nullable();
            $table->float('layer')->nullable();
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
        Schema::dropIfExists('gbdp_second_heat_treatments');
    }
};
