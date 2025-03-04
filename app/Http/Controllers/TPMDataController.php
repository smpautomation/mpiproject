<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TPMData;

class TPMDataController extends Controller
{
    public function index(){
        $tmpData = TPMData::all();
        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully',
            'data' => $tmpData
        ], 200);
    }

    public function show($id){
        $tmpData = TPMData::find($id);
        if(!empty($tmpData)){
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

    }

    public function store($id){

    }

    public function update($id){

    }

    public function destroy($id){

    }
}
