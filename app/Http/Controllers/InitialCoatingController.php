<?php

namespace App\Http\Controllers;

use App\Models\InitialCoating;
use Illuminate\Http\Request;

class InitialCoatingController extends Controller
{
    /**
     * Display a listing of all initial coatings.
     */
    public function index()
    {
        return response()->json(InitialCoating::all());
    }

    /**
     * Store a newly created initial coating in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
            'lot_no' => 'nullable|string|max:50',
            'coating_date' => 'nullable|date',
            'machine_no' => 'nullable|string',
            'slurry_lot_no' => 'nullable|string',
            'loader_operator' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'min_tb_content' => 'nullable|string',
            'sample_qty' => 'nullable|string',
            'total_magnet_weight' => 'nullable|string',
            'checker_operator' => 'nullable|string',
            'time_start' => 'nullable|date_format:H:i',
            'time_finished' => 'nullable|date_format:H:i',
            'coating_data' => 'nullable|array',
            'maximum' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'average' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);

        $initialCoating = InitialCoating::create($validated);

        return response()->json($initialCoating, 201);
    }

    /**
     * Display the specified initial coating.
     */
    public function show(InitialCoating $initialCoating)
    {
        return response()->json($initialCoating);
    }

    /**
     * Update the specified initial coating in storage.
     */
    public function update(Request $request, InitialCoating $initialCoating)
    {
        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
            'lot_no' => 'nullable|string|max:50',
            'coating_date' => 'nullable|date',
            'machine_no' => 'nullable|string',
            'slurry_lot_no' => 'nullable|string',
            'loader_operator' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'min_tb_content' => 'nullable|string',
            'sample_qty' => 'nullable|string',
            'total_magnet_weight' => 'nullable|string',
            'checker_operator' => 'nullable|string',
            'time_start' => 'nullable|date_format:H:i:s',
            'time_finished' => 'nullable|date_format:H:i:s',
            'coating_data' => 'nullable|array',
            'maximum' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'average' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);

        $initialCoating->update($validated);

        return response()->json($initialCoating);
    }

    /**
     * Remove the specified initial coating from storage.
     */
    public function destroy(InitialCoating $initialCoating)
    {
        $initialCoating->delete();
        return response()->json(['message' => 'Initial coating deleted successfully.']);
    }

    public function checkDuplicateLot(Request $request)
    {
        $request->validate([
            'lot_no'     => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ]);

        // Check based only on lot_no
        $lotExists = InitialCoating::where('lot_no', $request->lot_no)->exists();

        // Check based on the pair (model_name + lot_no)
        $pairExists = InitialCoating::where('lot_no', $request->lot_no)
            ->where('model_name', $request->model_name)
            ->exists();

        return response()->json([
            'duplicate_lot'  => $lotExists,
            'duplicate_pair' => $pairExists,
        ]);
    }

    public function fetchCoatingSummaryData(Request $request)
    {
        $validated = $request->validate([
            'lot_no'     => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ]);

        $record = InitialCoating::where('lot_no', $validated['lot_no'])
            ->where('model_name', $validated['model_name'])
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'No record found.'
            ], 404);
        }

        return response()->json($record);
    }
}
