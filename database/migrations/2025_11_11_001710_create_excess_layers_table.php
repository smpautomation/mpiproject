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
        Schema::create('excess_layers', function (Blueprint $table) {
            $table->id();
            $table->string('furnace')->nullable();
            $table->string('mass_prod')->nullable();
            $table->integer('layer')->nullable();
            $table->json('layer_data')->nullable();
            $table->integer('available_boxes')->nullable();
            $table->timestamps();

            // Add composite index for faster queries
            $table->index(['furnace', 'mass_prod', 'layer']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excess_layers');
    }
};
