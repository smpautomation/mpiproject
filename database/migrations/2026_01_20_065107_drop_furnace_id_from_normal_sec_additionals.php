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
        Schema::table('normal_sec_additionals', function (Blueprint $table) {
            $table->dropForeign(['furnace_id']); // if it has FK
            $table->dropColumn('furnace_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('normal_sec_additionals', function (Blueprint $table) {
            $table->unsignedBigInteger('furnace_id')->nullable();

            $table->foreign('furnace_id')
                  ->references('furnace_id')
                  ->on('furnace_data');
        });
    }
};
