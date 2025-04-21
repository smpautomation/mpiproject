<?php

namespace App\Http\Controllers;

use App\Models\DataInstructionsAggregate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DataInstructionsAggregateController extends Controller
{
    public function index(Request $request)
    {
        $serial = $request->query('serial');
        if ($serial) {
            try {
                $dataInstructionsAggregate = DataInstructionsAggregate::where('tpm_data_serial', $serial)->first();
                if (!$dataInstructionsAggregate) {
                    return response()->json([
                        'status' => false,
                        'message' => "Data Instructions Aggregate with serial: {$serial} not found",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Data Instructions Aggregate with serial: {$serial} found successfully",
                    'data' => [
                        $dataInstructionsAggregate
                    ]
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Data Instructions Aggregate',
                    'error' => $e->getMessage(),
                ], 500);
            }
        } else {
            try {
                $dataInstructionsAggregate = DataInstructionsAggregate::all();
                if ($dataInstructionsAggregate->isEmpty()) {
                    return response()->json([
                        'status' => false,
                        'message' => "No Data Instructions Aggregatefound",
                    ], 404);
                }
                return response()->json([
                    'status' => true,
                    'message' => "Data Instructions Aggregateretrieved successfully",
                    'data' => $dataInstructionsAggregate
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Data Instructions Aggregate',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $dataInstructionsAggregate = DataInstructionsAggregate::find($id);
            if (!$dataInstructionsAggregate) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Instructions Aggregate with id: {$id} not found",
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => "Data Instructions Aggregate with id: {$id} found successfully",
                'data' => [
                    $dataInstructionsAggregate
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $dataInstructionsInput = [
                'average' => $request->input('average'),
                'minimum' => $request->input('minimum'),
                'maximum' => $request->input('maximum'),
                'cpk' => $request->input('cpk'),
                'judgement' => $request->input('judgement'),
                'judgement_remarks' => $request->input('judgement_remarks'),
            ];
            $dataInstructionsAggregate = DataInstructionsAggregate::create($dataInstructionsInput);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Data Instructions Aggregatecreated successfully",
                'data' => [
                    $dataInstructionsAggregate
                ]
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error creating Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try{
            $dataInstructionsAggregate = DataInstructionsAggregate::where('tpm_data_serial', $id)->first();
            if (!$dataInstructionsAggregate) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Instructions Aggregate with serial: {$id} not found",
                ], 404);
            }
            $inputData = $request->all();
            $dataInstructionsAggregate->update($inputData);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Data Instructions Aggregateupdated successfully",
                'data' => [
                    $dataInstructionsAggregate
                ]
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $dataInstructionsAggregate = DataInstructionsAggregate::where('tpm_data_serial', $id)->first();
            if (!$dataInstructionsAggregate) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Instructions Aggregate with serial: {$id} not found",
                ], 404);
            }
            $dataInstructionsAggregate->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data Instructions Aggregate deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting Data Instructions Aggregate',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
