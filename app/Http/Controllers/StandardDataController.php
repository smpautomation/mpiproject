<?php

namespace App\Http\Controllers;

use App\Models\StandardData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StandardDataController extends Controller
{
    public function index(Request $request)
    {
        $serial = $request->query('serial');
        if ($serial) {
            try{
                $standardData = StandardData::where('tpm_data_serial', $serial)->first();
                if (!$standardData) {
                    return response()->json([
                        'status' => false,
                        'message' => "Standard Data with serial: {$serial} not found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Standard Data with serial: {$serial} found successfully",
                    'data' => [
                        $standardData
                    ]
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving tmp Data',
                    'error' => $e->getMessage(),
                ], 500);
            }
            
        } else {
            try{
                $standardData = StandardData::all();
                if ($standardData->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => "No Standard Data found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Standard Data retrieved successfully",
                    'data' => $standardData
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Standard Data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $standardData = StandardData::findOrFail($id);
            if(!$standardData) {
                return response()->json([
                    'status' => false,
                    'message' => "Standard Data with id: {$id} not found",
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => "Standard Data with id: {$id} found successfully",
                'data' => [
                    $standardData
                ]
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving Standard Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $standardDataInputs= [
                'brcpk_ucl' => $request->input('brcpk_ucl'),
                'brcpk_lcl' => $request->input('brcpk_lcl'),
                'lsl_corner' => $request->input('lsl_corner'),
                'lsl_surface' => $request->input('lsl_surface'),
                'lsl_core' => $request->input('lsl_core'),
            ];
            $standardData = StandardData::create($standardDataInputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Standard Data created successfully",
                'data' => [
                    $standardData
                ]
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error creating Standard Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $standardData = StandardData::where('tpm_data_serial', $id)->first();

            $inputData = $request->all();
            $standardData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Standard Data updated successfully",
                'data' => [
                    $standardData
                ]
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error updating Standard Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $standardData = StandardData::where('tpm_data_serial',$id)->get();
            $standardData->delete();
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
