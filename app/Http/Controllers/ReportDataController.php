<?php

namespace App\Http\Controllers;

use App\Models\ReportData;
use App\Models\TPMData;
use App\Models\TPMDataCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportDataController extends Controller
{
    public function index(Request $request)
    {
        try{
            $serial_no = $request->query("serial");
            if($serial_no){
                $reportData = ReportData::where('tpm_data_serial', $serial_no)->get();
                if(!$reportData->isEmpty()){
                    return response()->json([
                        'status' => true,
                        'message' => "Report Data with Serial: {$serial_no} fetched successfully",
                        'data'=> $reportData
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message'=> "Report Data with Serial: {$serial_no} cannot be found"
                    ], 500);
                }
            }else{
                $reportData = ReportData::all();
                return response()->json([
                    'status' => true,
                    'message' => 'Report Data fetched successfully',
                    'data'=> $reportData
                ], 200);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> "Report Data could not be fetched",
                "error"=> $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try{
            $reportData = ReportData::find($id);
            if(!empty($reportData)){
                return response()->json([
                    "status"=> true,
                    "message"=> "Report Data with ID: {$id} fetched successfully",
                    "data"=> $reportData
                ], 200);
            }else{
                return response()->json([
                    "status"=> false,
                    "message"=> "Report Data with ID: {$id} not found",
                ], 404);
            }

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> "Report Data could not be fetched",
                "error"=> $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $reportDataInputs = [
                "model" => $request->input("model"),
                "material_code" => $request->input("material_code"),
                "partial_number" => $request->input("partial_number"),
                "total_quantity"=> $request->input("total_quantity"),
                "pulse_tracer_machine_number" => $request->input("pulse_tracer_machine_number"),
                "date" => $request->input("date"),
                "shift" => $request->input("shift"),
                "operator" => $request->input("operator"),
                "magnetic_property_data" => $request->input("magnetic_property_data"),
                "length" => $request->input("length"),
                "width" => $request->input("width"),
                "thickness" => $request->input("thickness"),
                "material_grade" => $request->input("material_grade"),
                "mpi_sample_quantity"=> $request->input("mpi_sample_quantity"),
                "std_dev"=> $request->input("std_dev"),
                "cpu"=> $request->input("cpu"),
                "cpl"=> $request->input("cpl"),
                "cpk"=> $request->input("cpk"),
                "br_cpk_remarks"=> $request->input("br_cpk_remarks"),
                "remarks"=> $request->input("remarks"),
                "remarks_display" => $request->input("remarks_display"),
                "smp_judgement"=> $request->input("smp_judgement"),
                "prepared_by"=> $request->input("prepared_by"),
                "checked_by"=> $request->input("checked_by"),
                "approved_by" => $request->input("approved_by"),
                "prepared_by_date"=> $request->input("prepared_by_date"),
                "checked_by_date"=> $request->input("checked_by_date"),
                "approved_by_date" => $request->input("approved_by_date"),
                "note_reason_reject" => $request->input("note_reason_reject"),
                "oven_machine_no" => $request->input("oven_machine_no"),
                "time_loading" => $request->input("time_loading"),
                "temp_time_loading" => $request->input("temp_time_loading"),
                "date_oven_info" => $request->input("date_oven_info"),
                "time_unloading" => $request->input("time_unloading"),
                "temp_time_unloading" => $request->input("temp_time_unloading"),
                "shift_oven_info" => $request->input("shift_oven_info"),
                "operator_oven_info" => $request->input("operator_oven_info"),
                "withCarmark" => $request->input("withCarmark"),
                "with_sec_additionals" => $request->input("with_sec_additionals"),
                "coating_completed" => $request->input("coating_completed"),
                "heat_treatment_completed" => $request->input("heat_treatment_completed"),
            ];
            $reportData = ReportData::create($reportDataInputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Report Data saved successfully',
                'data' => $reportData
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Report Data could not be saved",
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $reportData = ReportData::where('tpm_data_serial', $id)->first();

            $inputData = $request->all();
            $reportData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Report Data updated successfully',
                'data' => $reportData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Report Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $reportData = ReportData::where('serial_no',$id)->get();
            $reportData->delete();
            return response()->json([
                'status' => true,
                'message' => 'tmp Data deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting tmp Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getViewListChecked()
    {
        // Step 1: Get all serials where prepared_by_date is not null or empty
        $serials = ReportData::whereNotNull('prepared_by_date')
            ->pluck('tpm_data_serial')
            ->toArray();

        $serials = array_map('strval', $serials);

        Log::info('ApprovalViewList: Serials found', ['count' => count($serials), 'serials' => $serials]);

        if (empty($serials)) {
            return response()->json([]);
        }

        // Step 2
        $tpmData = TPMData::whereIn('serial_no', $serials)
            ->select('serial_no', 'mass_prod', 'layer_no', 'Tracer', 'sintering_furnace_no')
            ->get()
            ->keyBy('serial_no');
        Log::info('ApprovalViewList: TPMData fetched', ['count' => $tpmData->count()]);

        // Step 3
        $categoryData = TPMDataCategory::whereIn('tpm_data_serial', $serials)
            ->select('tpm_data_serial', 'actual_model', 'jhcurve_lotno')
            ->get()
            ->keyBy('tpm_data_serial');
        Log::info('ApprovalViewList: CategoryData fetched', ['count' => $categoryData->count()]);

        // Step 4
        $reportData = ReportData::whereIn('tpm_data_serial', $serials)
            ->select('tpm_data_serial', 'smp_judgement', 'prepared_by_firstname', 'prepared_by_surname', 'checked_by_date')
            ->get()
            ->keyBy('tpm_data_serial');
        Log::info('ApprovalViewList: ReportData fetched', ['count' => $reportData->count()]);

        // Step 5: Flatten merged result
        $flattened = collect($serials)->map(function ($serial) use ($tpmData, $categoryData, $reportData) {
            $tpm = $tpmData[$serial] ?? null;
            $cat = $categoryData[$serial] ?? null;
            $rep = $reportData[$serial] ?? null;

            return [
                'Mass_Production' => $tpm->mass_prod ?? null,
                'Layer' => $tpm->layer_no ?? null,
                'Serial_No' => $tpm->serial_no ?? $serial,
                'Model_Name' => $cat->actual_model ?? null,
                'Lot_No' => $cat->jhcurve_lotno ?? null,
                'Furnace_No' => $tpm->sintering_furnace_no ?? null,
                'Tracer_No' => $tpm->Tracer ?? null,
                'Prepared_By' => trim(($rep->prepared_by_firstname ?? '') . ' ' . ($rep->prepared_by_surname ?? '')) ?: null,
                'Status' => (bool) $rep?->checked_by_date,
                'SMP_Judgement' => $rep->smp_judgement ?? null,
            ];
        });

        return response()->json(['data' => $flattened->values()]);
    }

    public function getViewListPrepared()
    {
        // Step 1: Get all serials where prepared_by_date is not null or empty
        $serials = ReportData::whereNull('prepared_by_date') // null values
        ->orWhere('prepared_by_date', '')                // empty strings
        ->pluck('tpm_data_serial')
        ->toArray();

        $serials = array_map('strval', $serials);

        Log::info('ApprovalViewList: Serials found', ['count' => count($serials), 'serials' => $serials]);

        if (empty($serials)) {
            return response()->json([]);
        }

        // Step 2
        $tpmData = TPMData::whereIn('serial_no', $serials)
            ->select('serial_no', 'mass_prod', 'layer_no', 'Tracer', 'sintering_furnace_no')
            ->get()
            ->keyBy('serial_no');
        Log::info('ApprovalViewList: TPMData fetched', ['count' => $tpmData->count()]);

        // Step 3
        $categoryData = TPMDataCategory::whereIn('tpm_data_serial', $serials)
            ->select('tpm_data_serial', 'actual_model', 'jhcurve_lotno')
            ->get()
            ->keyBy('tpm_data_serial');
        Log::info('ApprovalViewList: CategoryData fetched', ['count' => $categoryData->count()]);

        // Step 4
        $reportData = ReportData::whereIn('tpm_data_serial', $serials)
            ->select('tpm_data_serial', 'smp_judgement', 'prepared_by_date')
            ->get()
            ->keyBy('tpm_data_serial');
        Log::info('ApprovalViewList: ReportData fetched', ['count' => $reportData->count()]);

        // Step 5: Flatten merged result
        $flattened = collect($serials)->map(function ($serial) use ($tpmData, $categoryData, $reportData) {
            $tpm = $tpmData[$serial] ?? null;
            $cat = $categoryData[$serial] ?? null;
            $rep = $reportData[$serial] ?? null;

            return [
                'Mass_Production' => $tpm->mass_prod ?? null,
                'Layer' => $tpm->layer_no ?? null,
                'Serial_No' => $tpm->serial_no ?? $serial,
                'Model_Name' => $cat->actual_model ?? null,
                'Lot_No' => $cat->jhcurve_lotno ?? null,
                'Furnace_No' => $tpm->sintering_furnace_no ?? null,
                'Tracer_No' => $tpm->Tracer ?? null,
                'Status' => (bool) $rep?->prepared_by_date,
                'SMP_Judgement' => $rep->smp_judgement ?? null,
            ];
        });

        return response()->json(['data' => $flattened->values()]);
    }
}
