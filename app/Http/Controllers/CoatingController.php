<?php

namespace App\Http\Controllers;

use App\Models\Coating;
use Illuminate\Http\Request;

class CoatingController extends Controller
{
    public function index()
    {
        return Coating::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'serial' => 'required|integer',
            'date' => 'required|date',
            'machine_no' => 'required|string',
            'slurry_lot_no' => 'required|string',
            'loader_operator' => 'required|string',
            'min_tb_content' => 'required|string',
            'sample_qty' => 'required|string',
            'total_magnet_weight' => 'required|string',
            'unloader_operator' => 'required|string',
            'coating_data' => 'required|array',
        ]);

        $coating = Coating::create([
            ...$validated,
            'coating_data' => json_encode($validated['coating_data']),
        ]);

        return response()->json($coating, 201);
    }

    public function show(Coating $coating)
    {
        return response()->json($coating);
    }

    public function update(Request $request, Coating $coating)
    {
        $validated = $request->validate([
            'serial' => 'sometimes|integer',
            'date' => 'sometimes|date',
            'machine_no' => 'sometimes|string',
            'slurry_lot_no' => 'sometimes|string',
            'loader_operator' => 'sometimes|string',
            'min_tb_content' => 'sometimes|string',
            'sample_qty' => 'sometimes|string',
            'total_magnet_weight' => 'sometimes|string',
            'unloader_operator' => 'sometimes|string',
            'coating_data' => 'sometimes|array',
        ]);

        if (isset($validated['coating_data'])) {
            $validated['coating_data'] = json_encode($validated['coating_data']);
        }

        $coating->update($validated);

        return response()->json($coating);
    }

    public function destroy(Coating $coating)
    {
        $coating->delete();

        return response()->json(null, 204);
    }
}
