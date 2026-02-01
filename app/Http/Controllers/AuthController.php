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
            'password' => ['required', 'confirmed', 'min:4'],
        ]);

        // Log the validated input
        //Log::info('Validated input: ', $validated);

        // List of employee IDs that get Automation access
        $automationIds = [
            '002089', '003890', '006105', '004779', '003754',
            '005452', '006644', '006645', '006374', '006545',
            '006731', '006571'
        ];
        //Log::info('Automation IDs: ' . implode(', ', $automationIds));

        $itadaniSan_id = [
            '005009'
        ];
        //Log::info('ItadaniSan IDs: ' . implode(', ', $itadaniSan_id));

        $rizzaEndaya_id = [
            '002202'
        ];

        $checkedByApprovers_id = [
            '003510','005496','004514'
        ];

        $preparedByApprovers_id = [
            '006704','006830','006748','006806','003513','004316',
            '004884','004025','004489','005828','004391','004608'
        ];

        $heatTreatmentUsers_id = [
            '4021','3358','6640','6699','6423','6528','3485','7003',
            '6747','6502','003485','002121','000021'
        ];

        $coatingUsers_id = [
            '4143','7060','2963','6729','7053','6996','4825','6903',
            '001808'
        ];

        $userAccess = null;

        // Log the employee_id being checked
        //Log::info('Checking employee_id: ' . $validated['employee_id']);

        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $automationIds, true)) {
            $userAccess = 'Automation';
            //Log::info('Employee ID matched Automation IDs. Setting access_type to "Automation".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $itadaniSan_id, true)) {
            $userAccess = 'Final Approver';
            //Log::info('Employee ID matched ItadaniSan ID. Setting access_type to "Final Approver".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $rizzaEndaya_id, true)) {
            $userAccess = 'Proxy Approver';
            //Log::info('Employee ID matched Ms Rizza ID. Setting access_type to "Proxy Approver".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $checkedByApprovers_id, true)) {
            $userAccess = 'Checking Approver';
            //Log::info('Employee ID matched Checking approver IDs. Setting access_type to "Checking Approver".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $preparedByApprovers_id, true)) {
            $userAccess = 'Preparation Approver';
            //Log::info('Employee ID matched Preparation approver IDs. Setting access_type to "Preparation Approver".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $heatTreatmentUsers_id, true)) {
            $userAccess = 'Heat Treatment';
            //Log::info('Employee ID matched Preparation approver IDs. Setting access_type to "Heat Treatment".');
        }
        if (isset($validated['employee_id']) && in_array($validated['employee_id'], $coatingUsers_id, true)) {
            $userAccess = 'Coating';
            //Log::info('Employee ID matched Preparation approver IDs. Setting access_type to "Coating".');
        }

        $user = User::create([
            'firstName' => $validated['firstName'],
            'surname' => $validated['surname'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'plant' => $validated['plant'] ?? null,
            'employee_id' => $validated['employee_id'] ?? null,
            'password' => Hash::make($validated['password']),
            'access_type' => $userAccess ?? 'Basic User',
        ]);

        // Log the created user's data
        //Log::info('User created: ', $user->toArray());

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
