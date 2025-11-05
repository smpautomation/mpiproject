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
        Schema::table('mass_productions', function (Blueprint $table) {
            $table->string('layer_1_format_type')->nullable()->after('mpi_sample_qty');
            $table->string('layer_2_format_type')->nullable()->after('layer_1_format_type');
            $table->string('layer_3_format_type')->nullable()->after('layer_2_format_type');
            $table->string('layer_4_format_type')->nullable()->after('layer_3_format_type');
            $table->string('layer_5_format_type')->nullable()->after('layer_4_format_type');
            $table->string('layer_6_format_type')->nullable()->after('layer_5_format_type');
            $table->string('layer_7_format_type')->nullable()->after('layer_6_format_type');
            $table->string('layer_8_format_type')->nullable()->after('layer_7_format_type');
            $table->string('layer_9_format_type')->nullable()->after('layer_8_format_type');
            $table->string('layer_9_5_format_type')->nullable()->after('layer_9_format_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_productions', function (Blueprint $table) {
            $table->dropColumn([
                'layer_1_format_type',
                'layer_2_format_type',
                'layer_3_format_type',
                'layer_4_format_type',
                'layer_5_format_type',
                'layer_6_format_type',
                'layer_7_format_type',
                'layer_8_format_type',
                'layer_9_format_type',
                'layer_9_5_format_type',
            ]);
        });
    }
};
