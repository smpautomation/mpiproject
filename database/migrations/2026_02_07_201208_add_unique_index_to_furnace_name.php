<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('furnace_data', function (Blueprint $table) {
            $table->unique('furnace_name', 'furnace_data_furnace_name_unique');
        });
    }

    public function down()
    {
        Schema::table('furnace_data', function (Blueprint $table) {
            $table->dropUnique('furnace_data_furnace_name_unique');
        });
    }

};
