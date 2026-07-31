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
        Schema::table('rob_model_ajs', function (Blueprint $table) {
            $table->float('br_rt_standardmax')->nullable();
            $table->float('br_rt_standardmin')->nullable();
            $table->float('br_vt_standard')->nullable();
            $table->float('hd5_standard')->nullable();
            $table->float('jd5_standard')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rob_model_ajs', function (Blueprint $table) {
            $table->dropColumn(['br_rt_standardmax','br_rt_standardmin','br_vt_standard','hd5_standard','jd5_standard']);
        });
    }
};
