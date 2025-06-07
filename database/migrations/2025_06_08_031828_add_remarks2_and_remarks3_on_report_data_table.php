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
            $table->string('remarks2')->nullable()->after('remarks');
            $table->string('remarks3')->nullable()->after('remarks2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn(['remarks2','remarks3']);
        });
    }
};
