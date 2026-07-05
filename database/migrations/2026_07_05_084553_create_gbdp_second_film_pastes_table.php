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
        Schema::create('gbdp_second_film_pastes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod')->nullable();
            $table->string('furnace')->nullable();
            $table->string('layer')->nullable();
            $table->string('second_mass_prod')->nullable();
            $table->string('second_furnace')->nullable();
            $table->string('second_layer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gbdp_second_film_pastes');
    }
};
