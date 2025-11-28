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
        Schema::create('initial_control_sheets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->json('layer_data')->nullable();
            $table->json('excess_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('initial_control_sheets');
    }
};
