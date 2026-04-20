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
        Schema::create('data_editor_logs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('mass_prod');
            $table->string('furnace');
            $table->string('layer');
            $table->text('user_reason');
            $table->text('user_corrective_action');
            $table->string('user_verified_by');
            $table->boolean('user_confirmation')->default(false);
            $table->string('log_remarks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_editor_logs');
    }
};
