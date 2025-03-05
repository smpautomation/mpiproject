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
        Schema::create('tpm_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date');
            $table->string('code_no');
            $table->string('order_no');
            $table->string('type');
            $table->string('press_1');
            $table->string('press_2');
            $table->string('machine_no');
            $table->string('sintering_furnace_no');
            $table->string('furnace_no');
            $table->string('zone');
            $table->string('pass_no');
            $table->string('Br');
            $table->string('4paiId');
            $table->string('iHc');
            $table->string('bHc');
            $table->string('BHMax');
            $table->string('Squareness');
            $table->string('4paiIs');
            $table->string('iHk');
            $table->string('4paiIa');
            $table->string('Density');
            $table->string('iHkiHc');
            $table->string('Br4pai');
            $table->string('iHr95');
            $table->string('iHr98');
            $table->string('Tracer');
            $table->string('HRX');
            $table->string('MRX');
            $table->string('HRY');
            $table->string('MRY');
            $table->string('IHKA');
            $table->string('MRA');
            $table->string('IHKB');
            $table->string('MRB');
            $table->string('IHKC');
            $table->string('MRC');
            $table->string('HR');
            $table->string('HRO');
            $table->json('x');
            $table->json('y');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmp_data');
    }
};
