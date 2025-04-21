<?php

namespace App\Http\Controllers;

use App\Models\MieGxDataInstructionsAggregate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MieGxDataInstructionsAggregateController extends Controller
{
    public function index(Request $request)
    {
        $serial = $request->query('serial');
        if ($serial) {
            try {
                $MieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::where('tpm_data_serial', $serial)->first();
                if (!$MieGxDataInstructionsAggregate) {
                    return response()->json([
                        'status' => false,
                        'message' => "MIE GX Data Instructions Aggregate with serial: {$serial} not found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "MIE GX Data Instructions Aggregate with serial: {$serial} found successfully",
                    'data' => [
                        $MieGxDataInstructionsAggregate
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving MIE GX Data Instructions Aggregate',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            try {
                $MieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::all();
                if ($MieGxDataInstructionsAggregate->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => "No MIE GX Data Instructions Aggregate found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "MIE GX Data Instructions Aggregate retrieved successfully",
                    'data' => $MieGxDataInstructionsAggregate
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving MIE GX Data Instructions Aggregate',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $MieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::find($id);
            if (!$MieGxDataInstructionsAggregate) {
                return response()->json([
                    'status' => false,
                    'message' => "MIE GX Data Instructions Aggregate with id: {$id} not found",
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => "MIE GX Data Instructions Aggregate with id: {$id} found successfully",
                'data' => [
                    $MieGxDataInstructionsAggregate
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving MIE GX Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $dataInstructionsInput = [
                'gx_average' => $request->input('gx_average'),
                'gx_minimum' => $request->input('gx_minimum'),
                'gx_maximum' => $request->input('gx_maximum')
            ];
            $MieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::create($dataInstructionsInput);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "MIE GX Data Instructions Aggregate created successfully",
                'data' => [
                    $MieGxDataInstructionsAggregate
                ]
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error creating MIE GX Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $MieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::where('tpm_data_serial', $id)->first();
            if (!$MieGxDataInstructionsAggregate) {
                return response()->json([
                    'status' => false,
                    'message' => "MIE GX Data Instructions Aggregate with serial: {$id} not found",
                ], 404);
            }
            $inputData = $request->all();
            $MieGxDataInstructionsAggregate->update($inputData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "MIE GX Data Instructions Aggregate updated successfully",
                'data' => [
                    $MieGxDataInstructionsAggregate
                ]
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating MIE GX Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $MieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::where('tpm_data_serial', $id)->first();
            if (!$MieGxDataInstructionsAggregate) {
                return response()->json([
                    'status' => false,
                    'message' => "MIE GX Data Instructions Aggregate with serial: {$id} not found",
                ], 404);
            }
            $MieGxDataInstructionsAggregate->delete();
            return response()->json([
                'status' => true,
                'message' => 'MIE GX Data Instructions Aggregate deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting MIE GX Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
