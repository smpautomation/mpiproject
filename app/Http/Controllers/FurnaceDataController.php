<?php

namespace App\Http\Controllers;

use App\Models\FurnaceData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FurnaceDataController extends Controller
{
    public function index(Request $request)
    {
        $furnace_no = $request->query("furnace");
        if($furnace_no){
            try{
                $furnaceData = FurnaceData::with(['layerData'])
                                    ->where('furnace_no',  $furnace_no)->get();

                if(!$furnaceData->isEmpty()){

                    return response()->json([
                        'status' => true,
                        'message' => 'Furnace data found successfully',
                        'data' => $furnaceData
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Furnace data not found for this furnace number.'
                    ], 404);
                }
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while retrieving Furnace data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }else{
            try{
                $furnaceData = FurnaceData::all();
                return response()->json([
                    'status' => true,
                    'message' => 'Furnace Datas retrieved successfully',
                    'data' => [
                        'Furnace Data' => $furnaceData
                    ]
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Furnace Data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $furnaceData = FurnaceData::with(['LayerData'])
                                ->find($id);

            if(!empty($fucnaceData)){
                $layerData = $furnaceData->layerData ?? 'No layer available';

                return response()->json([
                    'status' => true,
                    'message' => 'Furnace data found successfully',
                    'data' => $furnaceData
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Furnace data not found for this furnace number.'
                ], 404);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving Furnace data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $furnaceData = FurnaceData::create([
                'furnace_id' => $request->input('furnace_id'),
                'furnace_name' => $request->input('furnace_name'),
                'description' => $request->input('description')
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Furnace data created successfully',
                'data' => $furnaceData
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating Furnace data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $furnaceData = FurnaceData::findorfail( $id );

            $inputData = $request->all();
            $furnaceData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Furnace Data updated successfully',
                'data' => $furnaceData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Furnace Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $furnaceData = FurnaceData::findorfail($id);
            $furnaceData->delete();
            return response()->json([
                'status' => true,
                'message' => 'Furnace Data deleted successfully'
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
