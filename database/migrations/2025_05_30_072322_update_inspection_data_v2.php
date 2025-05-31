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
            // New columns
            $table->boolean('is_automotive')->default(false);
            $table->string('encoded_by')->nullable();

            $table->dropColumn('ihc_ihk');
            $table->dropColumn('br_4-pie-la');

            // Changing existing columns
            $table->string('time_unloading')->nullable()->change();
            $table->string('time_loading')->nullable()->change();
            $table->string('temperature_1')->nullable()->change();
            $table->string('temperature_2')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspection_data', function (Blueprint $table) {
            // Revert new columns
            $table->dropColumn('is_automotive');
            $table->dropColumn('encoded_by');

            $table->string('ihc_ihk')->nullable();
            $table->string('br_4-pie-la')->nullable();

            // Revert column types
            $table->time('time_unloading')->change();
            $table->time('time_loading')->change();
            $table->double('temperature_1')->change();
            $table->double('temperature_2')->change();
        });
    }
};
