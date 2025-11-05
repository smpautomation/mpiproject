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
        Schema::table('film_pasting_data', function (Blueprint $table) {
            $table->string('unloader_operator')->nullable()->after('loader_operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('film_pasting_data', function (Blueprint $table) {
            $table->dropColumn('unloader_operator');
        });
    }
};
