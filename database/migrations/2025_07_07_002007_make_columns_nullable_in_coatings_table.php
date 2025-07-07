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
            $table->float('maximum')->nullable()->change();
            $table->float('minimum')->nullable()->change();
            $table->float('average')->nullable()->change();
            $table->string('remarks')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coatings', function (Blueprint $table) {
            $table->float('maximum')->nullable(false)->change();
            $table->float('minimum')->nullable(false)->change();
            $table->float('average')->nullable(false)->change();
            $table->string('remarks')->nullable(false)->change();
        });
    }
};
