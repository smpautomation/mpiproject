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
        Schema::create('breaklot_addtnl_format_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('furnace')->nullable();
            $table->string('mass_prod')->nullable();
            $table->string('layer')->nullable();
            $table->string('model')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('format_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('breaklot_addtnl_format_types');
    }
};
