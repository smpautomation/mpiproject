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
        Schema::table('initial_film_pastings', function (Blueprint $table) {
            $table->json('hourly_checking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('initial_film_pastings', function (Blueprint $table) {
            $table->dropColumn('hourly_checking');
        });
    }
};
