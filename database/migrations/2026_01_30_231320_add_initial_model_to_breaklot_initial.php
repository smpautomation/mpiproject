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
        Schema::table('breaklot_initial_lots', function (Blueprint $table) {
            $table->string('initial_model')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('breaklot_initial_lots', function (Blueprint $table) {
            $table->dropColumn('initial_model');
        });
    }
};
