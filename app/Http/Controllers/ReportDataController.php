<?php

namespace App\Http\Controllers;

use App\Models\ReportData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                "smp_judgement"=> $request->input("smp_judgement"),
                "prepared_by"=> $request->input("prepared_by"),
                "checked_by"=> $request->input("checked_by"),
                "approved_by" => $request->input("approved_by"),
                "prepared_by_date"=> $request->input("prepared_by_date"),
                "checked_by_date"=> $request->input("checked_by_date"),
                "approved_by_date" => $request->input("approved_by_date"),
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
}
