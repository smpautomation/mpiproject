<?php

namespace App\Http\Controllers;

use App\Models\HeatTreatment;
use Illuminate\Http\Request;

class HeatTreatmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return HeatTreatment::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial' => 'required|integer',
            'furnace_machine' => 'nullable|string',
            'cycle_no' => 'nullable|string',
            'batch_cycle_no' => 'nullable|string',
            'pattern_no' => 'nullable|string',
            'date_start' => 'nullable|date',
            'time_start' => 'nullable|date_format:H:i',
            'loader' => 'nullable|string',
            'date_finish' => 'nullable|date',
            'time_finish' => 'nullable|date_format:H:i',
            'unloader' => 'nullable|string',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'magnet_box_location' => 'nullable|array',
        ]);

        $heatTreatment = HeatTreatment::create([
            ...$validated,
            'magnet_box_location' => json_encode($validated['magnet_box_location']),
        ]);

        return response()->json($heatTreatment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HeatTreatment $heatTreatment)
    {
        return response()->json($heatTreatment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HeatTreatment $heatTreatment)
    {
        $validated = $request->validate([
            'serial' => 'sometimes|integer',
            'furnace_machine' => 'sometimes|nullable|string',
            'cycle_no' => 'sometimes|nullable|string',
            'batch_cycle_no' => 'sometimes|nullable|string',
            'pattern_no' => 'sometimes|nullable|string',
            'date_start' => 'sometimes|nullable|date',
            'time_start' => 'sometimes|nullable|date_format:H:i',
            'loader' => 'sometimes|nullable|string',
            'date_finish' => 'sometimes|nullable|date',
            'time_finish' => 'sometimes|nullable|date_format:H:i',
            'unloader' => 'sometimes|nullable|string',
            'cycle_pattern' => 'sometimes|nullable|string',
            'current_pattern' => 'sometimes|nullable|string',
            'magnet_box_location' => 'sometimes|nullable|array',
        ]);

        if (isset($validated['magnet_box_location'])) {
            $validated['magnet_box_location'] = json_encode($validated['magnet_box_location']);
        }

        $heatTreatment->update($validated);

        return response()->json($heatTreatment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeatTreatment $heatTreatment)
    {
        $heatTreatment->delete();

        return response()->json(null, 204);
    }
}