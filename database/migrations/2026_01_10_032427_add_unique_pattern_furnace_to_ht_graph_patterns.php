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
            $table->unique(['pattern_no', 'furnace_no'], 'ht_pattern_furnace_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ht_graph_patterns', function (Blueprint $table) {
            $table->dropUnique('ht_pattern_furnace_unique');
        });
    }
};
