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
        $user->tokens()->delete();

        $token = $user->createToken('api-token', [], now()->addMinutes(config('sanctum.expiration')));

        return response()->json([
            'status' => true,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in_minutes' => config('sanctum.expiration'),
            'user' => $user->only(['id', 'name', 'email', 'username']),
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
            'user' => $request->user()->only(['id', 'name', 'email', 'username']),
        ]);
    }
}
