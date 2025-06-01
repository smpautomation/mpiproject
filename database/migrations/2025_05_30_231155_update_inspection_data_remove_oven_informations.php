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
        Schema::table('inspection_data', function (Blueprint $table) {
            $table->dropColumn('time_loading');
            $table->dropColumn('time_unloading');
            $table->dropColumn('temperature_1');
            $table->dropColumn('temperature_2');
            $table->dropColumn('shift');
            $table->dropColumn('operator');

            $table->boolean('oven_machine_no')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_data', function (Blueprint $table) {
            $table->string('time_loading')->nullable();
            $table->string('time_unloading')->nullable();
            $table->string('temperature_1')->nullable();
            $table->string('temperature_2')->nullable();
            $table->string('shift')->nullable();
            $table->string('operator')->nullable();

            $table->string('oven_machine_no')->nullable();
        });
    }
};
