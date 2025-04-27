<?php

namespace App\Http\Controllers;

use App\Models\NormalSecAdditionals;
use App\Models\NSAAggregateFunctions;
use App\Models\NSACategory;
use App\Models\NSARemark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NormalSecAdditionalsController extends Controller
{
    public function index(Request $request){
        $serial_no = $request->query('serial');
        $report = $request->query('report');
        if (!$serial_no) {
            try{
                $nsaData = NormalSecAdditionals::all();
                $remarks = NSARemark::all();
                $aggregateFunctions = NSAAggregateFunctions::all();
                // if($report){
                //     $reportData = ReportData::all();
                // }
                return response()->json([
                    'status' => true,
                    'message' => 'NSA Datas retrieved successfully',
                    'data' => [
                        'NSAData' => $nsaData,
                        'remarks' => $remarks,
                        'aggregateFunctions' =>  $aggregateFunctions
                    ]
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving NSA Data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }else{
            try{
                $nsaData = NormalSecAdditionals::with($report ? ['remark', 'reportData', 'category'] : ['remark', 'category'])
                                    ->where('serial_no',  $serial_no)
                                    ->orderBy('zone', 'asc')
                                    ->get();

                if(!$nsaData->isEmpty()){
                    $NSAAggregateFunctions = NSAAggregateFunctions::where('nsa_serial', $serial_no)->get();
                    return response()->json([
                        'status' => true,
                        'message' => 'NSA data found successfully',
                        'data' => $nsaData, $NSAAggregateFunctions
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
    public function store(Request $request){
        DB::beginTransaction();

        try{
            $NSAInputs = [
                'date' => $request->input('date', null),
                'serial_no' => $request->input('serial_no', null),
                'code_no' => $request->input('code_no', null),
                'order_no' => $request->input('order_no', null),
                'type' => $request->input('type', null),
                'press_1' => $request->input('press_1', null),
                'press_2' => $request->input('press_2', null),
                'machine_no' => $request->input('machine_no', null),
                'sintering_furnace_no' => $request->input('sintering_furnace_no', null),
                'furnace_no' => $request->input('furnace_no', null),
                'zone' => $request->input('zone', null),
                'pass_no' => $request->input('pass_no', null),
                'Br' => $request->input('Br', null),
                '4paiId' => $request->input('4paiId', null),
                'iHc' => $request->input('iHc', null),
                'bHc' => $request->input('bHc', null),
                'BHMax' => $request->input('BHMax', null),
                'Squareness' => $request->input('Squareness', null),
                '4paiIs' => $request->input('4paiIs', null),
                'iHk' => $request->input('iHk', null),
                '4paiIa' => $request->input('4paiIa', null),
                'Density' => $request->input('Density', null),
                'iHkiHc' => $request->input('iHkiHc', null),
                'Br4pai' => $request->input('Br4pai', null),
                'iHr95' => $request->input('iHr95', null),
                'iHr98' => $request->input('iHr98', null),
                'Tracer' => $request->input('Tracer', null),
                'HRX' => $request->input('HRX', null),
                'MRX' => $request->input('MRX', null),
                'HRY' => $request->input('HRY', null),
                'MRY' => $request->input('MRY', null),
                'IHKA' => $request->input('IHKA', null),
                'MRA' => $request->input('MRA', null),
                'IHKB' => $request->input('IHKB', null),
                'MRB' => $request->input('MRB', null),
                'IHKC' => $request->input('IHKC', null),
                'MRC' => $request->input('MRC', null),
                'HR' => $request->input('HR', null),
                'HRO' => $request->input('HRO', null),
                'x' => $request->input('x', null),
                'y' => $request->input('y', null),
                'furnace_id' => $request->input('furnace_id', null),
                'layer_no' => $request->input('layer_no', null),
                'temperature' => $request->input('temperature', null),
                'data_status' => $request->input('data_status', null)
            ];
            $nsaData = NormalSecAdditionals::create($NSAInputs);
            $remarkData = [
                'nsa_id' => $nsaData->id,
                'Br_remarks' => $request->input('Br_remarks', null),
                '4paiId_remarks' => $request->input('4paiId_remarks', null),
                'iHc_remarks' => $request->input('iHc_remarks', null),
                'bHc_remarks' => $request->input('bHc_remarks', null),
                'BHMax_remarks' => $request->input('BHMax_remarks', null),
                'Squareness_remarks' => $request->input('Squareness_remarks', null),
                '4paiIs_remarks' => $request->input('4paiIs_remarks', null),
                'iHk_remarks' => $request->input('iHk_remarks', null),
                '4paiIa_remarks' => $request->input('4paiIa_remarks', null),
                'Density_remarks' => $request->input('Density_remarks', null),
                'iHkiHc_remarks' => $request->input('iHkiHc_remarks', null),
                'Br4pai_remarks' => $request->input('Br4pai_remarks', null),
                'iHr95_remarks' => $request->input('iHr95_remarks', null),
                'iHr98_remarks' => $request->input('iHr98_remarks', null),
                'Tracer_remarks' => $request->input('Tracer_remarks', null),
                'HRX_remarks' => $request->input('HRX_remarks', null),
                'MRX_remarks' => $request->input('MRX_remarks', null),
                'HRY_remarks' => $request->input('HRY_remarks', null),
                'MRY_remarks' => $request->input('MRY_remarks', null),
                'IHKA_remarks' => $request->input('IHKA_remarks', null),
                'MRA_remarks' => $request->input('MRA_remarks', null),
                'IHKB_remarks' => $request->input('IHKB_remarks', null),
                'MRB_remarks' => $request->input('MRB_remarks', null),
                'IHKC_remarks' => $request->input('IHKC_remarks', null),
                'MRC_remarks' => $request->input('MRC_remarks', null),
                'HR_remarks' => $request->input('HR_remarks', null),
                'HRO_remarks' => $request->input('HRO_remarks', null),
            ];
            $remark = NSARemark::create($remarkData);

            $checkNSAAggregateFunctions = NSAAggregateFunctions::where('nsa_serial', $nsaData->serial_no)->exists();
            if(!$checkNSAAggregateFunctions){
                try{
                    $NSAAggragateFunctionsInput = [
                        'nsa_serial' => $nsaData->serial_no,
                    ];
                    $NSAAggragateFunctions = NSAAggregateFunctions::create($NSAAggragateFunctionsInput);
                }catch(\Exception $e){

                }
            }

            $checkNSACategory = NSACategory::where('nsa_serial', $nsaData->serial_no)->exists();
            if(!$checkNSACategory){
                try{
                    $NSACategoryInputs = [
                        'nsa_serial' => $nsaData->serial_no,
                    ];
                    $NSACategory = NSACategory::create($NSACategoryInputs);
                }catch(\Exception $e){

                }
            }




            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'NSA Data created successfully',
                'data' => [
                    $nsaData,
                    $remark,
                    $checkNSAAggregateFunctions ?? $NSAAggragateFunctions,
                    $checkNSACategory ?? $NSACategory
                    ]
            ], 201);
        }catch(\Exception $e){
            // If an error occurs, roll back the transaction
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
            $aggregateFunctions = NSAAggregateFunctions::where( 'nsa_serial',$id )
                                ->first();
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
    public function destroy($id){
        try{
            $nsaData = NormalSecAdditionals::findorfail($id);
            $nsaData->delete();
            return response()->json([
                'status' => true,
                'message' => 'NSA Data deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting NSA Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
