<?php

namespace App\Http\Controllers;

use App\Models\NormalSecAdditionals;
use App\Models\NSAAggregateFunctions;
use App\Models\NSACategory;
use App\Models\NSARemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NormalSecAdditionalsController extends Controller
{
    public function index(Request $request)
    {
        $serial_no = $request->query('serial');
        $set_no = $request->query('set');
        $report = $request->query('report');

        try {
            if (!$serial_no) {
                // Return all NSA data
                $nsaData = NormalSecAdditionals::all();
                $remarks = NSARemark::all();
                $aggregateFunctions = NSAAggregateFunctions::all();
                $nsaCategory = NSACategory::all();

                return response()->json([
                    'status' => true,
                    'message' => 'NSA Datas retrieved successfully',
                    'data' => [
                        'NSAData' => $nsaData,
                        'remarks' => $remarks,
                        'aggregateFunctions' => $aggregateFunctions,
                        'nsaCategory' => $nsaCategory
                    ]
                ], 200);
            }

            // Base query for this serial
            $query = NormalSecAdditionals::with($report ? ['remark', 'reportData', 'category'] : ['remark', 'category'])
                        ->where('serial_no', $serial_no)
                        ->orderBy('zone', 'asc');

            if ($set_no) {
                // If set_no is provided, filter by it (existing behavior)
                $query->where('set_no', $set_no);
            }

            $nsaData = $query->get();

            if ($nsaData->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => $set_no
                        ? 'NSA data not found for this serial and set number.'
                        : 'NSA data not found for this serial number.'
                ], 404);
            }

            // Include aggregate functions and category info
            $NSAAggregateFunctions = NSAAggregateFunctions::where('nsa_serial', $serial_no)->get();
            $nsaCategory = NSACategory::all();

            return response()->json([
                'status' => true,
                'message' => $set_no
                    ? 'NSA data found successfully for this set.'
                    : 'NSA data found successfully for all sets.',
                'data' => [
                    'NSAData' => $nsaData,
                    'aggregateFunctions' => $NSAAggregateFunctions,
                    'nsaCategory' => $nsaCategory
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving NSA data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function show($id){
        try{
            $nsaData = NormalSecAdditionals::with(['remark'])
                                ->find($id);

            if(!empty($nsaData)){
                $remark = $nsaData->remark ?? 'No remark available';
                $NSAAggregateData = NSAAggregateFunctions::where('serial_no', $nsaData->serial_no)->get();

                return response()->json([
                    'status' => true,
                    'message' => 'NSA data found successfully',
                    'data' => $nsaData, $NSAAggregateData
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'NSA data not found for this serial number.'
                ], 404);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving NSA data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $NSAInputs = $request->only([
                'date',
                'serial_no',
                'set_no',
                'set_name',
                'code_no',
                'order_no',
                'type',
                'press_1',
                'press_2',
                'machine_no',
                'sintering_furnace_no',
                'furnace_no',
                'zone',
                'pass_no',
                'Br',
                '4paiId',
                'iHc',
                'bHc',
                'BHMax',
                'Squareness',
                '4paiIs',
                'iHk',
                '4paiIa',
                'Density',
                'iHkiHc',
                'Br4pai',
                'iHr95',
                'iHr98',
                'Tracer',
                'HRX',
                'MRX',
                'HRY',
                'MRY',
                'IHKA',
                'MRA',
                'IHKB',
                'MRB',
                'IHKC',
                'MRC',
                'HR',
                'HRO',
                'x',
                'y',
                'furnace',
                'mass_prod',
                'layer_no',
                'temperature',
                'data_status',
            ]);

            $nsaData = NormalSecAdditionals::create($NSAInputs);

            $remarkData = array_merge(
                [
                    'nsa_id'  => $nsaData->id,
                    'nsa_set' => $nsaData->set_no,
                ],
                $request->only([
                    'Br_remarks',
                    '4paiId_remarks',
                    'iHc_remarks',
                    'bHc_remarks',
                    'BHMax_remarks',
                    'Squareness_remarks',
                    '4paiIs_remarks',
                    'iHk_remarks',
                    '4paiIa_remarks',
                    'Density_remarks',
                    'iHkiHc_remarks',
                    'Br4pai_remarks',
                    'iHr95_remarks',
                    'iHr98_remarks',
                    'Tracer_remarks',
                    'HRX_remarks',
                    'MRX_remarks',
                    'HRY_remarks',
                    'MRY_remarks',
                    'IHKA_remarks',
                    'MRA_remarks',
                    'IHKB_remarks',
                    'MRB_remarks',
                    'IHKC_remarks',
                    'MRC_remarks',
                    'HR_remarks',
                    'HRO_remarks',
                ])
            );

            $remark = NSARemark::create($remarkData);

            $aggregate = NSAAggregateFunctions::firstOrCreate(
                [
                    'nsa_serial' => $nsaData->serial_no,
                    'nsa_set'    => $nsaData->set_no,
                ]
            );

            $category = NSACategory::firstOrCreate(
                [
                    'nsa_serial' => $nsaData->serial_no,
                ]
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'NSA Data created successfully',
                'data' => [
                    $nsaData,
                    $remark,
                    $aggregate,
                    $category,
                ],
            ], 201);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error creating NSA Data and remark',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function updateNSAData(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $nsaData = NormalSecAdditionals::findorfail( $id );

            $inputData = $request->all();
            $nsaData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'NSA Data updated successfully',
                'data' => $nsaData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating NSA Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateNSA_MiasFactor(Request $request)
    {
        DB::beginTransaction();

        try {
            $serialNo = $request->input('serial_no');
            $setNo = $request->input('set_no');

            $inputData = $request->except(['serial_no', 'set_no']);

            /*Log::info("Attempting NSA update", [
                'serial_no' => $serialNo,
                'set_no' => $setNo,
                'payload' => $inputData,
            ]);*/

            $affectedRows = NormalSecAdditionals::where('serial_no', $serialNo)
                ->where('set_no', $setNo)
                ->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "NSA Data updated successfully. Rows affected: {$affectedRows}",
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating NSA Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateRemarks(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $nsaData = NormalSecAdditionals::findorfail( $id );

            $remarks = $nsaData->remark;
            $remarksData = $request->all();
            $remarks->update($remarksData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Remarks updated successfully',
                'data' => $nsaData->remark
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Remarks',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateAggregateFunctions(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $aggregateFunctions = NSAAggregateFunctions::find($id);
            $aggregateFields = $request->all();
            $aggregateFunctions->update($aggregateFields);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Aggregate Functions updated successfully',
                'data' => $aggregateFunctions
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Aggregate Functions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function updateCategory(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $category = NSACategory::where( 'nsa_serial',$id )
                                ->first();
            $categoryFields = $request->all();
            $category->update($categoryFields);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
                'data' => $category
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Category',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMaxSet(Request $request)
    {
        $serial = $request->query('serial');

        $maxSet = NormalSecAdditionals::where('serial_no', $serial)
            ->max('set_no');

        return response()->json([
            'status' => true,
            'message' => 'Maximum set retrieved successfully.',
            'data' => [
                'max_set' => $maxSet ?? 0,
            ],
        ]);
    }

    public function destroy($serial, $set)
    {
        try {

            $deleted = NormalSecAdditionals::where('serial_no', $serial)
                ->where('set_no', $set)
                ->delete();

            if ($deleted === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'NSA Data not found.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'NSA Data deleted successfully.'
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting NSA Data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroyAggregate($id)
    {
        try {
            $aggregate = NSAAggregateFunctions::findOrFail($id);
            $aggregate->delete();

            return response()->json([
                'status' => true,
                'message' => 'NSA aggregate record deleted successfully.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'NSA aggregate record not found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting NSA aggregate record.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteCategory($id)
    {
        try {
            $category = NSACategory::findOrFail($id);
            $category->delete();

            return response()->json([
                'status' => true,
                'message' => "NSA Category with ID {$id} deleted successfully."
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => "NSA Category with ID {$id} not found."
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting NSA Category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
