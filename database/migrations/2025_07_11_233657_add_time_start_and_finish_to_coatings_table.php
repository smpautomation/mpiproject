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
        Schema::table('coatings', function (Blueprint $table) {
            $table->time('time_start')->nullable()->after('checker_operator');
            $table->time('time_finish')->nullable()->after('time_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coatings', function (Blueprint $table) {
            $table->dropColumn(['time_start', 'time_finish']);
        });
    }
};
