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
        Schema::table('tpm_data', function (Blueprint $table) {
            $table->string('layer_no')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tpm_data', function (Blueprint $table) {
            $table->unsignedBigInteger('layer_no')->nullable()->change();
        });
    }
};
