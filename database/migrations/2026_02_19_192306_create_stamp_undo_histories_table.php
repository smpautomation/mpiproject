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
        Schema::create('stamp_undo_histories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod')->nullable();
            $table->string('furnace')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('model_name')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('stamp_undone')->nullable();
            $table->string('undo_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stamp_undo_histories');
    }
};
