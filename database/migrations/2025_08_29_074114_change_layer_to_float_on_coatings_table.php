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
        Schema::table('coatings', function (Blueprint $table) {
            $table->string('mass_prod')->nullable()->after('updated_at')->change();
            $table->float('layer')->nullable()->after('mass_prod')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coatings', function (Blueprint $table) {
            $table->unsignedSmallInteger('layer')->nullable()->change();
            $table->string('mass_prod')->nullable()->change();
        });
    }
};
