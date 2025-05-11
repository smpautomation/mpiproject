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
        Schema::create('normal_sec_additionals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('serial_no')->index();
            $table->unsignedBigInteger('set_no')->index()->nullable();
            $table->string('set_name')->nullable();
            $table->string('code_no')->nullable();
            $table->string('order_no')->nullable();
            $table->string('type')->nullable();
            $table->string('press_1')->nullable();
            $table->string('press_2')->nullable();
            $table->string('machine_no')->nullable();
            $table->string('sintering_furnace_no')->nullable();
            $table->string('furnace_no')->nullable();
            $table->string('zone')->nullable();
            $table->string('pass_no')->nullable();
            $table->string('Br')->nullable();
            $table->string('4paiId')->nullable();
            $table->string('iHc')->nullable();
            $table->string('bHc')->nullable();
            $table->string('BHMax')->nullable();
            $table->string('Squareness')->nullable();
            $table->string('4paiIs')->nullable();
            $table->string('iHk')->nullable();
            $table->string('4paiIa')->nullable();
            $table->string('Density')->nullable();
            $table->string('iHkiHc')->nullable();
            $table->string('Br4pai')->nullable();
            $table->string('iHr95')->nullable();
            $table->string('iHr98')->nullable();
            $table->string('Tracer')->nullable();
            $table->string('HRX')->nullable();
            $table->string('MRX')->nullable();
            $table->string('HRY')->nullable();
            $table->string('MRY')->nullable();
            $table->string('IHKA')->nullable();
            $table->string('MRA')->nullable();
            $table->string('IHKB')->nullable();
            $table->string('MRB')->nullable();
            $table->string('IHKC')->nullable();
            $table->string('MRC')->nullable();
            $table->string('HR')->nullable();
            $table->string('HRO')->nullable();
            $table->json('x')->nullable();
            $table->json('y')->nullable();
            $table->unsignedBigInteger('furnace_id')->nullable();
            $table->unsignedBigInteger('layer_no')->nullable();
            $table->foreign('furnace_id')
                    ->references('furnace_id')
                    ->on('furnace_data')
                    ->onUpdate('cascade');
            $table->float('temperature')->nullable();
            $table->string('data_status')->nullable();
        });

        Schema::create('nsa_remarks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('nsa_id')->index();
            $table->string('Br_remarks')->nullable();
            $table->string('4paiId_remarks')->nullable();
            $table->string('iHc_remarks')->nullable();
            $table->string('bHc_remarks')->nullable();
            $table->string('BHMax_remarks')->nullable();
            $table->string('Squareness_remarks')->nullable();
            $table->string('4paiIs_remarks')->nullable();
            $table->string('iHk_remarks')->nullable();
            $table->string('4paiIa_remarks')->nullable();
            $table->string('Density_remarks')->nullable();
            $table->string('iHkiHc_remarks')->nullable();
            $table->string('Br4pai_remarks')->nullable();
            $table->string('iHr95_remarks')->nullable();
            $table->string('iHr98_remarks')->nullable();
            $table->string('Tracer_remarks')->nullable();
            $table->string('HRX_remarks')->nullable();
            $table->string('MRX_remarks')->nullable();
            $table->string('HRY_remarks')->nullable();
            $table->string('MRY_remarks')->nullable();
            $table->string('IHKA_remarks')->nullable();
            $table->string('MRA_remarks')->nullable();
            $table->string('IHKB_remarks')->nullable();
            $table->string('MRB_remarks')->nullable();
            $table->string('IHKC_remarks')->nullable();
            $table->string('MRC_remarks')->nullable();
            $table->string('HR_remarks')->nullable();
            $table->string('HRO_remarks')->nullable();
        });

        Schema::create('nsa_aggregate_functions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('nsa_serial')->unique();
            $table->foreign('nsa_serial')
                    ->references('serial_no')
                    ->on('normal_sec_additionals')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('nsa_set');
            $table->foreign('nsa_set')
                    ->references('set_no')
                    ->on('normal_sec_additionals')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->json('average')->nullable();
            $table->json('maximum')->nullable();
            $table->json('minimum')->nullable();
            $table->json('ng_counter')->nullable();
        });

        Schema::create('nsa_category', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('nsa_serial')->index();
            $table->foreign('nsa_serial')
                    ->references('serial_no')
                    ->on('normal_sec_additionals')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            $table->string('massprod_name')->nullable();
            $table->string('actual_model')->nullable();
            $table->string('jhcurve_lotno')->nullable();
            $table->string('mias_emp')->nullable();
            $table->string('factor_emp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normal_sec_additionals');
        Schema::dropIfExists('nsa_remarks');
        Schema::dropIfExists('nsa_aggregate_functions');
        Schema::dropIfExists('nsa_category');
    }
};
