<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
         $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // need password_confirmation field
            'plant' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'plant' => $validated['plant'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully',
            'data' => $user,
        ], 201);
    }

    public function update(Request $request, $id){
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'plant' => 'nullable|string|max:255',
            'employee_id' => 'nullable|string|max:255|unique:users,employee_id,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        // Update fields only if present in validated data
        foreach (['name', 'username', 'email', 'plant', 'employee_id'] as $field) {
            if (isset($validated[$field])) {
                $user->$field = $validated[$field];
            }
        }

        if (isset($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    public function destroy($id){
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ], 404);
        }

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully',
        ], 200);
    }
}
