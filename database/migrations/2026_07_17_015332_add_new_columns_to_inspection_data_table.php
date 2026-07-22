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
        Schema::table('inspection_data', function (Blueprint $table) {
            $table->json('pattern_list')->nullable();
            $table->json('layer_list')->nullable();
            $table->string('rule_encoded_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_data', function (Blueprint $table) {
            $table->dropColumn(['pattern_list','layer_list','rule_encoded_by']);
        });
    }
};
