<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Optional: delete old tokens for cleaner token management
        //$user->tokens()->delete();

        $token = $user->createToken('api-token', [], now()->addMinutes(config('sanctum.expiration')));

        return response()->json([
            'status' => true,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in_minutes' => config('sanctum.expiration'),
            'user' => $user->only(['id', 'firstName', 'surname', 'email', 'username', 'plant', 'employee_id']),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user()->only(['id', 'firstName', 'surname', 'email', 'username', 'plant', 'employee_id']),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'plant' => ['nullable', 'string', 'max:255'],
            'empId' => ['nullable', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'firstName' => $validated['firstName'],
            'surname' => $validated['surname'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'plant' => $validated['plant'] ?? null,
            'employee_id' => $validated['empId'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('api-token', [], now()->addMinutes(config('sanctum.expiration')));

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in_minutes' => config('sanctum.expiration'),
            'user' => $user->only(['id', 'firstName', 'surname', 'email', 'username', 'plant', 'employee_id']),
        ]);
    }
}
