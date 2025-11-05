<?php

namespace App\Http\Controllers;

use App\Models\SmpData;
use Illuminate\Http\Request;

class SmpDataController extends Controller
{

    public function index()
    {
        // Fetch all SMP data, ordered by latest
        $data = SmpData::latest()->get();

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string|max:255',
            'layer' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'special_instruction' => 'nullable|string|max:255',
        ]);

        $smpData = SmpData::create($validated);

        return response()->json([
            'message' => 'SMP data created successfully.',
            'data' => $smpData,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SmpData $smpData)
    {
        return response()->json($smpData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SmpData $smpData)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string|max:255',
            'layer' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:255',
            'special_instruction' => 'nullable|string|max:255',
        ]);

        $smpData->update($validated);

        return response()->json([
            'message' => 'SMP data updated successfully.',
            'data' => $smpData,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SmpData $smpData)
    {
        $smpData->delete();

        return response()->json([
            'message' => 'SMP data deleted successfully.',
        ]);
    }
}
