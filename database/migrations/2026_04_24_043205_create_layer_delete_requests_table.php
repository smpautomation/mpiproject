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
        Schema::create('layer_delete_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod');
            $table->string('furnace');
            $table->string('layer');
            $table->string('request_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layer_delete_requests');
    }
};
