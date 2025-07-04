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
            'furnace_machine' => 'required|string',
            'cycle_no' => 'required|string',
            'batch_cycle_no' => 'required|string',
            'pattern_no' => 'required|string',
            'date_start' => 'required|date',
            'time_start' => 'required|date_format:H:i',
            'loader' => 'required|string',
            'date_finish' => 'required|date',
            'time_finish' => 'required|date_format:H:i',
            'unloader' => 'required|string',
            'cycle_pattern' => 'required|string',
            'current_pattern' => 'required|string',
            'magnet_box_location' => 'required|array',
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
            'furnace_machine' => 'sometimes|string',
            'cycle_no' => 'sometimes|string',
            'batch_cycle_no' => 'sometimes|string',
            'pattern_no' => 'sometimes|string',
            'date_start' => 'sometimes|date',
            'time_start' => 'sometimes|date_format:H:i',
            'loader' => 'sometimes|string',
            'date_finish' => 'sometimes|date',
            'time_finish' => 'sometimes|date_format:H:i',
            'unloader' => 'sometimes|string',
            'cycle_pattern' => 'sometimes|string',
            'current_pattern' => 'sometimes|string',
            'magnet_box_location' => 'sometimes|array',
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