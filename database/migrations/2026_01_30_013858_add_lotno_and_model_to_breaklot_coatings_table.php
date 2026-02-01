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
        Schema::table('breaklot_coatings', function (Blueprint $table) {
            if (!Schema::hasColumn('breaklot_coatings', 'lot_no')) {
                $table->string('lot_no')->after('layer_code')->nullable();
            }
            // Add 'model' only if it doesn't exist
            if (!Schema::hasColumn('breaklot_coatings', 'model')) {
                $table->string('model')->after('layer_code')->nullable();
            }
            if (Schema::hasColumn('breaklot_coatings', 'initial_lot')) {
                $table->dropColumn('initial_lot');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //Schema::table('breaklot_coatings', function (Blueprint $table) {
            //$table->dropColumn(['lot_no','model']);
        //});
    }
};
