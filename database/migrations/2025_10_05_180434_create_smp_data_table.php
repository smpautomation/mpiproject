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
        Schema::create('smp_data', function (Blueprint $table) {
            $table->id();
            $table->string('mass_prod')->nullable();
            $table->string('layer')->nullable();
            $table->string('remarks')->nullable();
            $table->string('special_instruction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smp_data');
    }
};
