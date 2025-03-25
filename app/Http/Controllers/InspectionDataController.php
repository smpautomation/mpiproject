<?php

namespace App\Http\Controllers;

use App\Models\InspectionData;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InspectionDataController extends Controller
{
    public function index(Request $request)
    {
        try{
            $inspectionData = InspectionData::all();
            return response()->json([
                'status' => true,
                'message' => 'Inspection Data fetched successfully',
                'data'=> $inspectionData
            ], 200); 
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> "Inspection Data could not be fetched",
                "error"=> $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try{
            $inspectionData = InspectionData::find($id);
            if(!empty($inspectionData)){
                return response()->json([
                    "status"=> true,
                    "message"=> "Inspection Data with ID: {$id} fetched successfully",
                    "data"=> $inspectionData
                ], 200);
            }else{
                return response()->json([
                    "status"=> false,
                    "message"=> "Inspection Data with ID: {$id} not found",
                ], 404);
            }
            
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> "Inspection Data could not be fetched",
                "error"=> $e->getMessage()
            ], 500);    
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $inspectionDataInputs = [
                "model" => $request->input("model"),
                "lenght" => $request->input("lenght"),
                "width" => $request->input("width"),
                "thickness" => $request->input("thickness"),
                "material_grade" => $request->input("material_grade"),
                "br" => $request->input("br"),
                "ihc" => $request->input("ihc"),
                "ihk" => $request->input("ihk"),
                "oven_machine_no" => $request->input("oven_machine_no"),
                "time_loading" => $request->input("time_loading"),
                "temperature_1" => $request->input("temperature_1"),
                "date" => $request->input("date"),
                "time_unloading" => $request->input("time_unloading"),
                "temperature_2" => $request->input("temperature_2"),
                "shift" => $request->input("shift"),
                "operator" => $request->input("operator"),
                "mpi_sample" => $request->input("mpi_sample"),
                "ihc_ihk" => $request->input("ihc_ihk"),
                "br_4-pie-la" => $request->input("br_4-pie-la"),
            ];
            $inspectionData = InspectionData::create($inspectionDataInputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Inspection Data saved successfully',
                'data' => $inspectionData
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Inspection Data could not be saved",
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $inspectionData = InspectionData::findorfail( $id );

            $inputData = $request->all();
            $inspectionData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Inspection Data updated successfully',
                'data' => $inspectionData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Inspection Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $inspectionData = InspectionData::findorfail($id);
            $inspectionData->delete();
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
