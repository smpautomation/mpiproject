<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        // Drop FK from layer_data if it exists
        $fk = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'layer_data'
              AND CONSTRAINT_SCHEMA = DATABASE()
              AND COLUMN_NAME = 'furnace_id'
              AND REFERENCED_TABLE_NAME = 'furnace_data'
        ");

        if (!empty($fk)) {
            $fkName = $fk[0]->CONSTRAINT_NAME;
            DB::statement("ALTER TABLE layer_data DROP FOREIGN KEY `$fkName`");
        }

        // Drop FK from tpm_data if it exists
        $fk = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'tpm_data'
              AND CONSTRAINT_SCHEMA = DATABASE()
              AND COLUMN_NAME = 'furnace_id'
              AND REFERENCED_TABLE_NAME = 'furnace_data'
        ");

        if (!empty($fk)) {
            $fkName = $fk[0]->CONSTRAINT_NAME;
            DB::statement("ALTER TABLE tpm_data DROP FOREIGN KEY `$fkName`");
        }
    }

    public function down(): void
    {
        // Restore FKs on rollback
        Schema::table('layer_data', function (Blueprint $table) {
            $table->foreign('furnace_id')
                ->references('furnace_id')
                ->on('furnace_data')
                ->onUpdate('cascade');
        });

        Schema::table('tpm_data', function (Blueprint $table) {
            $table->foreign('furnace_id')
                ->references('furnace_id')
                ->on('furnace_data')
                ->onUpdate('cascade');
        });
    }
};
