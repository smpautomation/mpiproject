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
            $table->string('modified_smp_judgement')->nullable()->after('smp_judgement');
            $table->boolean('is_finalized')->default(false)->after('checked');
            $table->boolean('is_emailed')->default(false)->after('is_finalized');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn(['modified_smp_judgement','is_finalized','is_emailed']);
        });
    }
};
