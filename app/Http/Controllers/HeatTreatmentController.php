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
            'time_start' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'loader' => 'nullable|string',
            'date_finish' => 'nullable|date',
            'time_finish' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'unloader' => 'nullable|string',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'magnet_box_location' => 'nullable|array',
            'remarks' => 'nullable|string',
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
    public function update(Request $request, $serial)
    {
        // Force manual resolution
        $heatTreatment = HeatTreatment::where('serial', $serial)->firstOrFail();

        $validated = $request->validate([
            'serial' => 'sometimes|integer',
            'furnace_machine' => 'sometimes|nullable|string',
            'cycle_no' => 'sometimes|nullable|string',
            'batch_cycle_no' => 'sometimes|nullable|string',
            'pattern_no' => 'sometimes|nullable|string',
            'date_start' => 'sometimes|nullable|date',
            'time_start' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'loader' => 'sometimes|nullable|string',
            'date_finish' => 'sometimes|nullable|date',
            'time_finish' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'unloader' => 'sometimes|nullable|string',
            'cycle_pattern' => 'sometimes|nullable|string',
            'current_pattern' => 'sometimes|nullable|string',
            'magnet_box_location' => 'sometimes|nullable|array',
            'remarks' => 'sometimes|nullable|string',
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
