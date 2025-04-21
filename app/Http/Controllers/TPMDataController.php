<?php

namespace App\Http\Controllers;

use App\Models\DataInstructions;
use App\Models\DataInstructionsAggregate;
use App\Models\MieGxDataInstructions;
use App\Models\MieGxDataInstructionsAggregate;
use Illuminate\Http\Request;
use App\Models\TPMData;
use App\Models\TPMDataRemark;
use App\Models\TPMDataAggregateFunctions;
use App\Models\ReportData;
use App\Models\StandardData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Sleep;
use Illuminate\Support\Facades\Validator;

class TPMDataController extends Controller
{
    public function index(Request $request){
        $serial_no = $request->query('serial');
        $report = $request->query('report');
        if (!$serial_no) {
            try{
                $tpmData = TPMData::all();
                $remarks = TPMDataRemark::all();
                $aggregateFunctions = TPMDataAggregateFunctions::all();
                if($report){
                    $reportData = ReportData::all();
                }
                return response()->json([
                    'status' => true,
                    'message' => 'TPM Datas retrieved successfully',
                    'data' => [
                        'tpmData' => $tpmData,
                        'remarks' => $remarks,
                        'aggregateFunctions' =>  $aggregateFunctions
                    ]
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving tmp Data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }else{
            try{
                $tpmData = TPMData::where('serial_no',  $serial_no)
                                    ->orderBy('zone', 'asc')
                                    ->get();

                if(!$tpmData->isEmpty()){
                    $tpmDataRemarks = TPMDataRemark::where('tpm_data_serial', $serial_no)->get();
                    $tpmDataAggregateFunctions = TPMDataAggregateFunctions::where('tpm_data_serial', $serial_no)->get();
                    return response()->json([
                        'status' => true,
                        'message' => 'TPM data found successfully',
                        'data' => [
                            'tpmData' => $tpmData,
                            'remarks' => $tpmDataRemarks,
                            'aggregateFunctions' =>  $tpmDataAggregateFunctions
                        ]
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'TPM data not found for this serial number.'
                    ], 404);
                }
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while retrieving TPM data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }

    public function show($id){
        try{
            $tpmData = TPMData::with(['remark'])
                                ->find($id);

            if(!empty($tpmData)){
                $remark = $tpmData->remark ?? 'No remark available';
                $tpmAggregateData = TPMDataAggregateFunctions::where('serial_no', $tpmData->serial_no)->get();

                return response()->json([
                    'status' => true,
                    'message' => 'TPM data found successfully',
                    'data' => $tpmData, $tpmAggregateData
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'TPM data not found for this serial number.'
                ], 404);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving TPM data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request){
        DB::beginTransaction();

        try{
            $tpmDataInputs = [
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
                'layer_no' => $request->input('layer_no', null)
            ];
            $tpmData = TPMData::create($tpmDataInputs);
            $remarkData = [
                'tpm_data_serial' => $tpmData->serial_no,
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
            $remark = TPMDataRemark::create($remarkData);

            $checkTpmDataAggregateFunctions = TPMDataAggregateFunctions::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkTpmDataAggregateFunctions){
                try{
                    $tpmAggragateFunctionsInput = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $tpmAggragateFunctions = TPMDataAggregateFunctions::create($tpmAggragateFunctionsInput);
                }catch(\Exception $e){

                }
            }

            $checkReportData = ReportData::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkReportData){
                try{
                    $reportDataInputs = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $reportData = ReportData::create($reportDataInputs);
                }catch(\Exception $e){

                }
            }

            $checkStandardData = StandardData::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkStandardData){
                try{
                    $standardDataInputs = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $standardData = StandardData::create($standardDataInputs);
                }catch(\Exception $e){

                }
            }

            $checkDataInstructions = DataInstructions::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkDataInstructions){
                try{
                    $dataInstructionsInputs = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $dataInstructions = DataInstructions::create($dataInstructionsInputs);
                }catch(\Exception $e){

                }
            }

            $checkDataInstructionsAggregate = DataInstructionsAggregate::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkDataInstructionsAggregate){
                try{
                    $dataInstructionsAggregateInputs = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $dataInstructionsAggregate = DataInstructionsAggregate::create($dataInstructionsAggregateInputs);
                }catch(\Exception $e){

                }
            }

            $checkMieGxDataInstructions = MieGxDataInstructions::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkMieGxDataInstructions){
                try{
                    $mieGxDataInstructionsInputs = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $mieGxDataInstructions = MieGxDataInstructions::create($mieGxDataInstructionsInputs);
                }catch(\Exception $e){

                }
            }
            $checkMieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::where('tpm_data_serial', $tpmData->serial_no)->exists();
            if(!$checkMieGxDataInstructionsAggregate){
                try{
                    $mieGxDataInstructionsAggregateInputs = [
                        'tpm_data_serial' => $tpmData->serial_no,
                    ];
                    $mieGxDataInstructionsAggregate = MieGxDataInstructionsAggregate::create($mieGxDataInstructionsAggregateInputs);
                }catch(\Exception $e){

                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'tmp Data created successfully',
                'data' => [
                    $tpmData,
                    $remark,
                    $checkTpmDataAggregateFunctions ?? $tpmAggragateFunctions,
                    $checkReportData ?? $reportData,
                    $checkStandardData ?? $standardData,
                    $checkDataInstructions ?? $dataInstructions,
                    $checkDataInstructionsAggregate ?? $dataInstructionsAggregate,
                    $checkMieGxDataInstructions ?? $mieGxDataInstructions,
                    $checkMieGxDataInstructionsAggregate ?? $mieGxDataInstructionsAggregate
                    ]
            ], 201);
        }catch(\Exception $e){
            // If an error occurs, roll back the transaction
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error creating TPM Data and remark',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    // public function update(Request $request, $serial_no){
    //     DB::beginTransaction();
    //     try {
    //         $tpmData = TPMData::find($serial_no);

    //         $tpmDataInputs = [];
    //         foreach ($request->all() as $key => $value) {
    //             // Skip remarks and aggregate function fields (they will be handled separately)
    //             if (strpos($key, 'remarks') === false && !in_array($key, ['average', 'maximum', 'minimum', 'ng_counter'])) {
    //                 $tpmDataInputs[$key] = $value;
    //             }
    //         }
    //         if (!empty($tpmDataInputs)) {
    //             $tpmData->update($tpmDataInputs);
    //         }

    //         $remarks = $tpmData->remark;
    //         $remarksFields = collect($request->all())
    //             ->filter(function ($value, $key) {
    //                 return strpos($key, '_remarks') !== false;  // Check if the field is a 'remarks' field
    //             });
    //         foreach ($remarksFields as $key => $value) {
    //             $remarks->$key = $value;
    //         }
    //         if ($remarksFields->isNotEmpty()) {
    //             $remarks->save();
    //         }

    //         $aggregateFields = ['average', 'maximum', 'minimum', 'ng_counter'];
    //         $aggregateFunctions = $tpmData->aggregateFunctions;
    //         foreach ($aggregateFields as $field) {
    //             if ($request->has($field)) {
    //                 $aggregateFunctions->$field = $request->input($field);
    //             }
    //         }
    //         $aggregateFunctions->save();

    //         DB::commit();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'tmp Data updated successfully',
    //             'data' => $tpmData
    //         ], 200);
    //     }catch(\Exception $e){
    //         DB::rollBack();
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Error updating tmp Data and remark',
    //             'error' => $e->getMessage(),
    //         ], 500);
    //     }

    // }


    public function updateTpmData(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tpmData = TPMData::findorfail( $id );

            $inputData = $request->all();
            $tpmData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'TPM Data updated successfully',
                'data' => $tpmData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating TPM Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update Remarks
    public function updateRemarks(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $tpmData = TPMData::findorfail( $id );

            $remarks = $tpmData->remark;
            $remarksData = $request->all();
            $remarks->update($remarksData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Remarks updated successfully',
                'data' => $tpmData->remark
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

    // Update Aggregate Functions
    public function updateAggregateFunctions(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $aggregateFunctions = TPMDataAggregateFunctions::where( 'tpm_data_serial',$id )
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

    public function destroy($id){
        try{
            $tpmData = TPMData::findorfail($id);
            $tpmData->delete();
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
