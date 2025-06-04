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
            $table->string('prepared_by_firstname')->nullable();
            $table->string('prepared_by_surname')->nullable();
            $table->string('checked_by_firstname')->nullable();
            $table->string('checked_by_surname')->nullable();
            $table->string('approved_by_firstname')->nullable();
            $table->string('approved_by_surname')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_data', function (Blueprint $table) {
            $table->dropColumn(['prepared_by_firstname','prepared_by_surname','checked_by_firstname','checked_by_surname','approved_by_firstname','approved_by_surname']);
        });
    }
};
