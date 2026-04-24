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
        Schema::create('field_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod');
            $table->string('furnace');
            $table->string('current_data');
            $table->string('new_data');
            $table->string('target_column');
            $table->string('request_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_edit_requests');
    }
};
