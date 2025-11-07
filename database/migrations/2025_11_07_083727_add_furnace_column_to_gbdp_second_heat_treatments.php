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
        Schema::table('gbdp_second_heat_treatments', function (Blueprint $table) {
            $table->string('furnace')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gbdp_second_heat_treatments', function (Blueprint $table) {
            $table->dropColumn('furnace');
        });
    }
};
