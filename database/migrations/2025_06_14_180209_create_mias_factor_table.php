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
        Schema::create('mias_factor', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('employee_name')->nullable();
            $table->string('employee_no')->nullable();
            $table->string('mias_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mias_factor');
    }
};
