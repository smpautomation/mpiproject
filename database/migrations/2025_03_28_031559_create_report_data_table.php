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
        Schema::create('report_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('tpm_data_serial')->unique();
            $table->string('model')->nullable();
            $table->string('material_code')->nullable();
            $table->string('partial_number')->nullable();
            $table->string('total_quantity')->nullable();
            $table->string('pulse_tracer_machine_number')->nullable();
            $table->date('date')->nullable();
            $table->string('shift')->nullable();
            $table->string('operator')->nullable();
            $table->json('magnetic_property_data')->nullable();
            $table->float('length')->nullable();
            $table->float('width')->nullable();
            $table->float('thickness')->nullable();
            $table->string('material_grade')->nullable();
            $table->string('mpi_sample_quantity')->nullable();
            $table->float('std_dev')->nullable();
            $table->float('cp')->nullable();
            $table->float('cpk')->nullable();
            $table->string('br_cpk_remarks')->nullable();
            $table->string('remarks')->nullable();
            $table->string('remarks_display')->nullable();
            $table->string('smp_judgement')->nullable();
            $table->string('prepared_by')->nullable();
            $table->string('checked_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->datetime('prepared_by_date')->nullable();
            $table->datetime('checked_by_date')->nullable();
            $table->datetime('approved_by_date')->nullable();
            $table->json('note_reason_reject')->nullable();
            $table->boolean('checked')->default(false);
            $table->foreign('tpm_data_serial')
                ->references('serial_no')
                ->on('tpm_data')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('oven_machine_no')->nullable();
            $table->string('time_loading')->nullable();
            $table->string('temp_time_loading')->nullable();
            $table->string('date_oven_info')->nullable();
            $table->string('time_unloading')->nullable();
            $table->string('temp_time_unloading')->nullable();
            $table->string('shift_oven_info')->nullable();
            $table->string('operator_oven_info')->nullable();
            $table->boolean('withCarmark')->nullable();
            $table->json('data_1x1x1_info')->nullable();
            $table->json('data_VT_info')->nullable();
            $table->json('data_iHc_cpk_info')->nullable();
            $table->json('data_GX_info')->nullable();
            $table->json('data_bh_info')->nullable();
            $table->json('data_VT2_info')->nullable();
            $table->json('data_ROB_info')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_data');
    }
};
