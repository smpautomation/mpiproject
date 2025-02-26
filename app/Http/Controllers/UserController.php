<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $user = User::all();
        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully',
            'data' => $user
        ], 200);
    }

    public function show($id){
        $user = User::find($id);
        if(!empty($user)){
            return response()->json([
                'status' => true,
                'message' => 'User found successfully',
                'data' => $user
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

    }

    public function store(Request $request){

    }

    public function update(Request $request, $id){

    }

    public function destroy($id){

    }
}
