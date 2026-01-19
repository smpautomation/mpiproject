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
        Schema::table('normal_sec_additionals', function (Blueprint $table) {
            $table->string('mass_prod')->after('furnace_id')->nullable();
            $table->string('furnace')->after('mass_prod')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('normal_sec_additionals', function (Blueprint $table) {
            $table->dropColumn(['mass_prod','furnace']);
        });
    }
};
