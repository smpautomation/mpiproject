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
        Schema::table('report_data', function (Blueprint $table) {
            $table->json('data_br_cpk_info')->nullable()->after('data_tsi_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn('data_br_cpk_info');
        });
    }
};
