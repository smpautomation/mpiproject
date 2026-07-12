<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gbdp_second_film_pastes', function (Blueprint $table) {
            $table->string('model')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('encoded_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('gbdp_second_film_pastes', function (Blueprint $table) {
            $table->dropColumn([
                'model',
                'lot_no',
                'encoded_by'
            ]);
        });
    }
};
