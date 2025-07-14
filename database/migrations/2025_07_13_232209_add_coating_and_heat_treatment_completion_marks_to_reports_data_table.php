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
            $table->boolean('coating_completed')->default(false)->after('is_finalized');
            $table->boolean('heat_treatment_completed')->default(false)->after('coating_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn('coating_completed');
            $table->dropColumn('heat_treatment_completed');
        });
    }
};
