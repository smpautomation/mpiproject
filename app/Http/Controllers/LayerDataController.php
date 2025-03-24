<?php

namespace App\Http\Controllers;

use App\Models\LayerData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LayerDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $layer_no = $request->query("layer");
        $furnace_no = $request->query("furnace");
        if($layer_no && $furnace_no){
            try{
                $layerData = LayerData::where(['furnace_id', 'layer_no'],  [$furnace_no, $layer_no])->get();

                if(!$layerData->isEmpty()){
                    return response()->json([
                        'status' => true,
                        'message' => 'Layer data found successfully',
                        'data' => $layerData
                    ], 200);
                }else{
                    return response()->json([
                        'status' => false,
                        'message' => 'Layer data not found for this layer number.'
                    ], 404);
                }
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while retrieving Layer data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }else{
            try{
                $layerData = LayerData::all();
                return response()->json([
                    'status' => true,
                    'message' => 'Layer Datas retrieved successfully',
                    'data' => [
                        'Layer Data' => $layerData
                    ]
                ], 200);
            }catch(\Exception $e){
                return response()->json([
                    'status' => false,
                    'message' => 'Error retrieving Layer Data',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    }
    public function show($id)
    {
        try{
            $layerData = LayerData::with(['furnaceData'])
                                ->find($id);

            if(!empty($layerData)){
                return response()->json([
                    'status' => true,
                    'message' => 'Layer data found successfully',
                    'data' => $layerData
                ], 200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Layer data not found for this layer id.'
                ], 404);
            }
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving layer data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            $layerData = LayerData::create([
                'furnace_id' => $request->input('furnace_id'),
                'layer_no' => $request->input('layer_no'),
                'layer_name' => $request->input('layer_name'),
                'description'=> $request->input('description')
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Layer data created successfully',
                'data' => $layerData
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating layer data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $layerData = layerData::findorfail( $id );

            $inputData = $request->all();
            $layerData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Layer Data updated successfully',
                'data' => $layerData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Layer Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $layerData = LayerData::findorfail($id);
            $layerData->delete();
            return response()->json([
                'status' => true,
                'message' => 'Layer Data deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting Layer Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
