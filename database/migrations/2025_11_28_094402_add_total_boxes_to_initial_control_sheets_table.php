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
        Schema::table('initial_control_sheets', function (Blueprint $table) {
            $table->integer('total_boxes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('initial_control_sheets', function (Blueprint $table) {
            $table->dropColumn('total_boxes');
        });
    }
};
