<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
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

        $token = $user->createToken('api-token', [], now()->addMinutes(config('sanctum.expiration')));

        return response()->json([
            'status' => true,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in_minutes' => config('sanctum.expiration'),
            'user' => $user->only(['id', 'firstName', 'surname', 'email', 'username', 'plant', 'employee_id', 'access_type']),
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
            'user' => $request->user()->only(['id', 'firstName', 'surname', 'email', 'username', 'plant', 'employee_id', 'access_type']),
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'plant' => ['nullable', 'string', 'max:255'],
            'employee_id' => ['nullable', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Log the validated input
        Log::info('Validated input: ', $validated);

        // List of employee IDs that get Automation access
        $automationIds = [
            '002089', '003890', '006105', '004779', '003754',
            '005452', '006644', '006645', '006374', '006545',
            '006731', '006571'
        ];
        Log::info('Automation IDs: ' . implode(', ', $automationIds));

        $itadaniSan_id = [
            '005009'
        ];
        Log::info('ItadaniSan IDs: ' . implode(', ', $itadaniSan_id));

        $userAccess = null;

        // Log the employee_id being checked
        Log::info('Checking employee_id: ' . $validated['employee_id']);

        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $automationIds, true)) {
            $userAccess = 'Automation';
            Log::info('Employee ID matched Automation IDs. Setting access_type to "Automation".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $itadaniSan_id, true)) {
            $userAccess = 'Final Approver';
            Log::info('Employee ID matched ItadaniSan IDs. Setting access_type to "Final Approver".');
        }

        $user = User::create([
            'firstName' => $validated['firstName'],
            'surname' => $validated['surname'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'plant' => $validated['plant'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'password' => Hash::make($validated['password']),
            'access_type' => $userAccess,
        ]);

        // Log the created user's data
        Log::info('User created: ', $user->toArray());

        $token = $user->createToken('api-token', [], now()->addMinutes(config('sanctum.expiration')));

        return response()->json([
            'status' => true,
            'message' => 'Registration successful',
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in_minutes' => config('sanctum.expiration'),
            'user' => $user->only(['id', 'firstName', 'surname', 'email', 'username', 'plant', 'employee_id', 'access_type']),
        ]);
    }
}
