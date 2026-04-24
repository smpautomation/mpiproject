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
        Schema::table('data_editor_logs', function (Blueprint $table) {
            $table->dropColumn('user_verified_by');
            $table->string('requested_by')->after('user_confirmation');
            $table->string('approved_by')->after('requested_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_editor_logs', function (Blueprint $table) {
            $table->string('user_verified_by');
            $table->dropColumn('requested_by');
            $table->dropColumn('approved_by');
        });
    }
};
