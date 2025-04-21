<?php

namespace App\Http\Controllers;

use App\Models\MieGxDataInstructions;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MieGxDataInstructionsController extends Controller
{
    public function index(Request $request)
    {
        $serial = $request->query('serial');
        if ($serial) {
            try {
                $MieGxDataInstructions = MieGxDataInstructions::where('tpm_data_serial', $serial)->first();
                if (!$MieGxDataInstructions) {
                    return response()->json([
                        'status' => false,
                        'message' => "Mie Gx Data Instructions with serial: {$serial} not found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Mie Gx Data Instructions with serial: {$serial} found successfully",
                    'data' => [
                        $MieGxDataInstructions
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Mie Gx Data Instructions',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            try {
                $MieGxDataInstructions = MieGxDataInstructions::all();
                if ($MieGxDataInstructions->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => "No Mie Gx Data Instructions found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Mie Gx Data Instructions retrieved successfully",
                    'data' => $MieGxDataInstructions
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Mie Gx Data Instructions',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $MieGxDataInstructions = MieGxDataInstructions::find($id);
            if (!$MieGxDataInstructions) {
                return response()->json([
                    'status' => false,
                    'message' => "Mie Gx Data Instructions with id: {$id} not found",
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => "Mie Gx Data Instructions with id: {$id} found successfully",
                'data' => [
                    $MieGxDataInstructions
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving Mie Gx Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $dataInstructionsInput = [
                'gx_br' => $request->input('ihc'),
                'gx_ihc' => $request->input('gx_ihc'),
                'gx_ihk' => $request->input('gx_ihk'),
            ];
            $MieGxDataInstructions = MieGxDataInstructions::create($dataInstructionsInput);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Mie Gx Data Instructions created successfully",
                'data' => [
                    $MieGxDataInstructions
                ]
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error creating Mie Gx Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $MieGxDataInstructions = MieGxDataInstructions::where('tpm_data_serial', $id)->first();
            if (!$MieGxDataInstructions) {
                return response()->json([
                    'status' => false,
                    'message' => "Mie Gx Data Instructions with serial: {$id} not found",
                ], 404);
            }
            $inputData = $request->all();
            $MieGxDataInstructions->update($inputData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Mie Gx Data Instructions updated successfully",
                'data' => [
                    $MieGxDataInstructions
                ]
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Mie Gx Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $MieGxDataInstructions = MieGxDataInstructions::where('tpm_data_serial', $id)->first();
            if (!$MieGxDataInstructions) {
                return response()->json([
                    'status' => false,
                    'message' => "Mie Gx Data Instructions with serial: {$id} not found",
                ], 404);
            }
            $MieGxDataInstructions->delete();
            return response()->json([
                'status' => true,
                'message' => 'Mie Gx Data Instructions deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting Mie Gx Data Instructions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
