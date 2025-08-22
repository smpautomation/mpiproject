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
        Schema::create('ht_graph_patterns', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('pattern_no');
            $table->string('furnace_no')->nullable();
            $table->string('encoded_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ht_graph_patterns');
    }
};
