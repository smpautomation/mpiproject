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
        Schema::table('ht_graph_patterns', function (Blueprint $table) {
            $table->float('pattern_no_hours')->nullable()->after('encoded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ht_graph_patterns', function (Blueprint $table) {
            $table->dropColumn('pattern_no_hours');
        });
    }
};
