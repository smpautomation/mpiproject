<?php

namespace App\Http\Controllers;

use App\Models\InitialControlSheet;
use Illuminate\Http\Request;

class InitialControlSheetController extends Controller
{
    /**
     * Display all records.
     */
    public function index()
    {
        return InitialControlSheet::all();
    }

    /**
     * Store a new record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name'  => 'required|string|max:255',
            'lot_no'      => 'required|string|max:50',

            // Optional
            'layer_data'  => 'nullable|array',
            'excess_data' => 'nullable|array',
        ]);

        $sheet = InitialControlSheet::create([
            'model_name'  => $validated['model_name'],
            'lot_no'      => $validated['lot_no'],
            'layer_data'  => $validated['layer_data'] ?? null,
            'excess_data' => $validated['excess_data'] ?? null,
        ]);

        return response()->json($sheet, 201);
    }

    /**
     * Display one record.
     */
    public function show(InitialControlSheet $initialControlSheet)
    {
        return $initialControlSheet;
    }

    /**
     * Update an existing record.
     */
    public function update(Request $request, InitialControlSheet $initialControlSheet)
    {
        $validated = $request->validate([
            'model_name' => 'sometimes|string|max:255',
            'lot_no'     => 'sometimes|string|max:50',
            'layer_data' => 'sometimes|array',
            'excess_data' => 'sometimes|array',
        ]);

        $initialControlSheet->update($validated);

        return response()->json($initialControlSheet);
    }

    /**
     * Delete a record.
     */
    public function destroy(InitialControlSheet $initialControlSheet)
    {
        $initialControlSheet->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }

    public function checkDuplicateLot(Request $request)
    {
        $request->validate([
            'lot_no' => 'required|string|max:50',
        ]);

        $exists = InitialControlSheet::where('lot_no', $request->lot_no)->exists();

        return response()->json([
            'duplicate_detected' => $exists
        ]);
    }

    public function fetchAllLotNumbers()
    {
        $records = InitialControlSheet::whereNotNull('lot_no')
            ->orderBy('created_at', 'desc') // newest first
            ->get();

        return response()->json($records);
    }
}
