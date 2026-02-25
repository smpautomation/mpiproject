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
            $table->json('data_bh_seg_info')->nullable()->after('data_ROB_info');
            $table->boolean('is_bh_format_seg')->default(false)->after('data_bh_seg_info');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn(['data_bh_seg_info', 'is_bh_format_seg']);
        });
    }
};
