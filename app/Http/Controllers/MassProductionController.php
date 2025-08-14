<?php

namespace App\Http\Controllers;

use App\Models\MassProduction;
use Illuminate\Http\Request;

class MassProductionController extends Controller
{
     public function index()
    {
        return MassProduction::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string|unique:mass_productions,mass_prod',
            'furnace' => 'nullable|string',
            'batch_cycle_no' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'cycle_no' => 'nullable|string',
            'pattern_no' => 'nullable|integer',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'date_start' => 'nullable|date',
            'time_start' => 'nullable',
            'date_finished' => 'nullable|date',
            'time_finished' => 'nullable',
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9.5' => 'nullable|json',
            'grand_total_weight' => 'nullable|numeric',
            'grand_total_quantity' => 'nullable|integer',
            'layer_1_serial' => 'nullable|integer',
            'layer_2_serial' => 'nullable|integer',
            'layer_3_serial' => 'nullable|integer',
            'layer_4_serial' => 'nullable|integer',
            'layer_5_serial' => 'nullable|integer',
            'layer_6_serial' => 'nullable|integer',
            'layer_7_serial' => 'nullable|integer',
            'layer_8_serial' => 'nullable|integer',
            'layer_9_serial' => 'nullable|integer',
            'layer_9.5_serial' => 'nullable|integer',
        ]);

        return MassProduction::create($validated);
    }

    public function show($id)
    {
        return MassProduction::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $production = MassProduction::findOrFail($id);

        $validated = $request->validate([
            'mass_prod' => 'required|string|unique:mass_productions,mass_prod,' . $id,
            'furnace' => 'nullable|string',
            'batch_cycle_no' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'cycle_no' => 'nullable|string',
            'pattern_no' => 'nullable|integer',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'date_start' => 'nullable|date',
            'time_start' => 'nullable',
            'date_finished' => 'nullable|date',
            'time_finished' => 'nullable',
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9.5' => 'nullable|json',
            'grand_total_weight' => 'nullable|numeric',
            'grand_total_quantity' => 'nullable|integer',
            'layer_1_serial' => 'nullable|integer',
            'layer_2_serial' => 'nullable|integer',
            'layer_3_serial' => 'nullable|integer',
            'layer_4_serial' => 'nullable|integer',
            'layer_5_serial' => 'nullable|integer',
            'layer_6_serial' => 'nullable|integer',
            'layer_7_serial' => 'nullable|integer',
            'layer_8_serial' => 'nullable|integer',
            'layer_9_serial' => 'nullable|integer',
            'layer_9.5_serial' => 'nullable|integer',
        ]);

        $production->update($validated);

        return $production;
    }

    public function destroy($id)
    {
        $production = MassProduction::findOrFail($id);
        $production->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function getByMassProd($massprod)
    {
        $record = MassProduction::where('mass_prod', $massprod)->first();
        if(!$record){
            return response()->json([
                'message' => "Mass Production record not found.",
            ], 404);
        }

        return response()->json($record);
    }
}
