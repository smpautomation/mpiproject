<?php

namespace App\Http\Controllers;

use App\Models\UserLog;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = UserLog::all();
        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'date' => 'nullable|date',
                'user' => 'required|string|max:255',
                'event' => 'required|string|max:255',
                'section' => 'required|string|max:255',
            ]);

            $log = UserLog::create($validatedData);
            return response()->json($log, 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the log',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserLog $userLog)
    {
        return response()->json($userLog);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserLog $userLog)
    {
        $validatedData = $request->validate([
            'date' => 'nullable|date',
            'user' => 'nullable|string|max:255',
            'event' => 'nullable|string|max:255',
            'section' => 'required|string|max:255',
        ]);

        $userLog->update($validatedData);
        return response()->json($userLog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserLog $userLog)
    {
        $userLog->delete();
        return response()->noContent();
    }

    /**
     * Display a listing of instruction logs filtered by section = Instructions with pagination.
     */
    public function instructions(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = UserLog::where('section', 'Instructions');

        // Apply search filter on database level
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('user', 'like', "%{$search}%")
                ->orWhere('event', 'like', "%{$search}%");
            });
        }

        // Apply date range filter on database level
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($logs);
    }
}
