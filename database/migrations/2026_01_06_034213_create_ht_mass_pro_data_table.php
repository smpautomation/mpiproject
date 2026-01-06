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
        Schema::create('ht_mass_pro_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('model_name')->nullable();
            $table->float('product_weight')->nullable();
            $table->float('2nd_sl')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ht_mass_pro_data');
    }
};
