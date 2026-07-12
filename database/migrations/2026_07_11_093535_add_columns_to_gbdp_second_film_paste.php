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
        Schema::table('gbdp_second_film_pastes', function (Blueprint $table) {
            $table->string('film_class')->nullable();
            $table->string('film_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gbdp_second_film_pastes', function (Blueprint $table) {
            $table->dropColumn([
                'film_class',
                'film_type'
            ]);
        });
    }
};
