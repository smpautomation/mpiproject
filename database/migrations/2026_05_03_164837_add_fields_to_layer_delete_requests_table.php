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
        Schema::table('layer_delete_requests', function (Blueprint $table) {
            $table->text('reason')->nullable();
            $table->text('corrective_action')->nullable();
            $table->string('status')->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('layer_delete_requests', function (Blueprint $table) {
            //
        });
    }
};
