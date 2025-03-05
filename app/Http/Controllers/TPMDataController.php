<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPMData;
use App\Models\TPMDataRemark;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TPMDataController extends Controller
{
    public function index(){
        try{
            $tmpData = TPMData::all();
            return response()->json([
                'status' => true,
                'message' => 'TPM Datas retrieved successfully',
                'data' => $tmpData
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving tmp Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id){
        try{
            $tmpData = TPMData::find($id);

            if(!empty($tmpData)){
                $remark = $tmpData->remark;
                return response()->json([
                    'status' => true,
                    'message' => 'tmp Data found successfully',
                    'data' => $tmpData
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'tmp Data not found'
                ], 404);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'tmp Data not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request){
        DB::beginTransaction();

        try{
            $tpmDataInputs = [
                'date' => $request->input('date', null),
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
            ];
            $tpmData = TPMData::create($tpmDataInputs);
            $remarkData = [
                'tpm_data_id' => $tpmData->id, // Reference the newly created TPMData ID
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

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'tmp Data created successfully',
                'data' => [$tpmData, $remark]
            ], 201);
        }catch(\Exception $e){
            // If an error occurs, roll back the transaction
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Error creating tmp Data and remark',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function update(Request $request, $id){
        DB::beginTransaction();
        try{
            $tpmData = TPMData::find($id);
            $tpmDataInputs = [
                'date' => $request->input('date', $tpmData->date),
                'code_no' => $request->input('code_no', $tpmData->code_no),
                'order_no' => $request->input('order_no', $tpmData->order_no),
                'type' => $request->input('type', $tpmData->type),
                'press_1' => $request->input('press_1', default: $tpmData->press_1),
                'press_2' => $request->input('press_2', $tpmData->press_2),
                'machine_no' => $request->input('machine_no', $tpmData->machine_no),
                'sintering_furnace_no' => $request->input('sintering_furnace_no', $tpmData->sintering_furnace_no),
                'furnace_no' => $request->input('furnace_no', $tpmData->furnace_no),
                'zone' => $request->input('zone', $tpmData->zone),
                'pass_no' => $request->input('pass_no', $tpmData->pass_no),
                'Br' => $request->input('Br', $tpmData->Br),
                '4paiId' => $request->input('4paiId', $tpmData['4paiId']),
                'iHc' => $request->input('iHc', $tpmData->iHc),
                'bHc' => $request->input('bHc', default: $tpmData->bHc),
                'BHMax' => $request->input('BHMax', $tpmData->BHMax),
                'Squareness' => $request->input('Squareness', $tpmData->Squareness),
                '4paiIs' => $request->input('4paiIs', $tpmData['4paiIs']),
                'iHk' => $request->input('iHk', $tpmData->iHk),
                '4paiIa' => $request->input('4paiIa', $tpmData['4paiIa']),
                'Density' => $request->input('Density', $tpmData->Density),
                'iHkiHc' => $request->input('iHkiHc', $tpmData->iHkiHc),
                'Br4pai' => $request->input('Br4pai', $tpmData->Br4pai),
                'iHr95' => $request->input('iHr95', $tpmData->iHr95),
                'iHr98' => $request->input('iHr98', $tpmData->iHr98),
                'Tracer' => $request->input('Tracer', $tpmData->Tracer),
                'HRX' => $request->input('HRX', $tpmData->HRX),
                'MRX' => $request->input('MRX', $tpmData->MRX),
                'HRY' => $request->input('HRY', $tpmData->HRY),
                'MRY' => $request->input('MRY', $tpmData->MRY),
                'IHKA' => $request->input('IHKA', $tpmData->IHKA),
                'MRA' => $request->input('MRA', $tpmData->MRA),
                'IHKB' => $request->input('IHKB', $tpmData->IHKB),
                'MRB' => $request->input('MRB', $tpmData->MRB),
                'IHKC' => $request->input('IHKC', $tpmData->IHKC),
                'MRC' => $request->input('MRC', $tpmData->MRC),
                'HR' => $request->input('HR', $tpmData->HR),
                'HRO' => $request->input('HRO', $tpmData->HRO),
            ];
            $tpmData->update($tpmDataInputs);
            $remarks = $tpmData->remark;
            $remarkData = [
                'Br_remarks' => $request->input('Br_remarks', $remarks->Br_remarks),
                '4paiId_remarks' => $request->input('4paiId_remarks', $remarks['4paiId_remarks']),
                'iHc_remarks' => $request->input('iHc_remarks', $remarks->iHc_remarks),
                'bHc_remarks' => $request->input('bHc_remarks', $remarks->bHc_remarks),
                'BHMax_remarks' => $request->input('BHMax_remarks', $remarks->BHMax_remarks),
                'Squareness_remarks' => $request->input('Squareness_remarks', $remarks->Squareness_remarks),
                '4paiIs_remarks' => $request->input('4paiIs_remarks', $remarks['4paiIs_remarks']),
                'iHk_remarks' => $request->input('iHk_remarks', $remarks->iHk_remarks),
                '4paiIa_remarks' => $request->input('4paiIa_remarks', $remarks['4paiIa_remarks']),
                'Density_remarks' => $request->input('Density_remarks', $remarks->Density_remarks),
                'iHkiHc_remarks' => $request->input('iHkiHc_remarks', $remarks->iHkiHc_remarks),
                'Br4pai_remarks' => $request->input('Br4pai_remarks', $remarks->Br4pai_remarks),
                'iHr95_remarks' => $request->input('iHr95_remarks', $remarks->iHr95_remarks),
                'iHr98_remarks' => $request->input('iHr98_remarks', $remarks->iHr98_remarks),
                'Tracer_remarks' => $request->input('Tracer_remarks', $remarks->Tracer_remarks),
                'HRX_remarks' => $request->input('HRX_remarks', $remarks->HRX_remarks),
                'MRX_remarks' => $request->input('MRX_remarks', $remarks->MRX_remarks),
                'HRY_remarks' => $request->input('HRY_remarks', $remarks->HRY_remarks),
                'MRY_remarks' => $request->input('MRY_remarks', $remarks->MRY_remarks),
                'IHKA_remarks' => $request->input('IHKA_remarks', $remarks->IHKA_remarks),
                'MRA_remarks' => $request->input('MRA_remarks', $remarks->MRA_remarks),
                'IHKB_remarks' => $request->input('IHKB_remarks', $remarks->IHKB_remarks),
                'MRB_remarks' => $request->input('MRB_remarks', $remarks->MRB_remarks),
                'IHKC_remarks' => $request->input('IHKC_remarks', $remarks->IHKC_remarks),
                'MRC_remarks' => $request->input('MRC_remarks', $remarks->MRC_remarks),
                'HR_remarks' => $request->input('HR_remarks', $remarks->HR_remarks),
                'HRO_remarks' => $request->input('HRO_remarks', $remarks->HRO_remarks),
            ];
            $remarks->update($remarkData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'tmp Data updated successfully',
                'data' => $tpmData
            ], 200);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating tmp Data and remark',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function destroy($id){
        try{
            $tpmData = TPMData::find($id);
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
