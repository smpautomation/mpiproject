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
        Schema::create('layer_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('layer_no');
            $table->string('layer_name');
            $table->string('description');
            $table->unsignedBigInteger('furnace_no');
            $table->foreign('furnace_no')->references('furnace_no')->on('furnace_data')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layer_data');
    }
};
