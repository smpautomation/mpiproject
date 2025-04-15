<?php

namespace App\Http\Controllers;

use App\Models\DataInstructions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataInstructionsController extends Controller
{
    public function index(Request $request)
    {
        $serial = $request->query('serial');
        if ($serial) {
            try {
                $dataInstructions = DataInstructions::where('tpm_data_serial', $serial)->first();
                if (!$dataInstructions) {
                    return response()->json([
                        'status' => false,
                        'message' => "Data Instructions with serial: {$serial} not found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Data Instructions with serial: {$serial} found successfully",
                    'data' => [
                        $dataInstructions
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Data Instructions',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            try {
                $dataInstructions = DataInstructions::all();
                if ($dataInstructions->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => "No Data Instructions found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Data Instructions retrieved successfully",
                    'data' => $dataInstructions
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Data Instructions',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $dataInstructions = DataInstructions::find($id);
            if (!$dataInstructions) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Instructions with id: {$id} not found",
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => "Data Instructions with id: {$id} found successfully",
                'data' => [
                    $dataInstructions
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $dataInstructionsInput = [
                'sample_code' => $request->input('sample_code'),
                'corner' => $request->input('corner'),
                'surface' => $request->input('surface'),
                'core' => $request->input('core'),
            ];
            $dataInstructions = DataInstructions::create($dataInstructionsInput);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Data Instructions created successfully",
                'data' => [
                    $dataInstructions
                ]
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error creating Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $dataInstructions = DataInstructions::where('tpm_data_serial', $id)->first();
            if (!$dataInstructions) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Instructions with serial: {$id} not found",
                ], 404);
            }
            $inputData = $request->all();
            $dataInstructions->update($inputData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Data Instructions updated successfully",
                'data' => [
                    $dataInstructions
                ]
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $dataInstructions = DataInstructions::where('tpm_data_serial', $id)->first();
            if (!$dataInstructions) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Instructions with serial: {$id} not found",
                ], 404);
            }
            $dataInstructions->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data Instructions deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
