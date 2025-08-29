<?php

namespace App\Http\Controllers;

use App\Models\Coating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoatingController extends Controller
{
    public function index()
    {
        return Coating::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|integer',
            'machine_no' => 'nullable|string',
            'slurry_lot_no' => 'nullable|string',
            'loader_operator' => 'nullable|string',
            'min_tb_content' => 'nullable|string',
            'sample_qty' => 'nullable|string',
            'total_magnet_weight' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'coating_data' => 'nullable|array',
            'checker_operator' => 'nullable|string',
            'time_start' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finish' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'maximum' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'average' => 'nullable|numeric',
            'remarks' => 'nullable|string',
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

    public function update(Request $request, $serial)
    {
        $coating = Coating::where('serial', $serial)->firstOrFail();

        $validated = $request->validate([
            'date' => 'sometimes|nullable|date',
            'mass_prod' => 'sometimes|string',
            'layer' => 'sometimes|integer',
            'machine_no' => 'sometimes|nullable|string',
            'slurry_lot_no' => 'sometimes|nullable|string',
            'loader_operator' => 'sometimes|nullable|string',
            'min_tb_content' => 'sometimes|nullable|string',
            'sample_qty' => 'sometimes|nullable|string',
            'total_magnet_weight' => 'sometimes|nullable|string',
            'unloader_operator' => 'sometimes|nullable|string',
            'coating_data' => 'sometimes|nullable|array',
            'checker_operator' => 'sometimes|nullable|string',
            'time_start' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finish' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'maximum' => 'sometimes|nullable|numeric',
            'minimum' => 'sometimes|nullable|numeric',
            'average' => 'sometimes|nullable|numeric',
            'remarks' => 'sometimes|nullable|string',
        ]);

        if (isset($validated['coating_data'])) {
            $validated['coating_data'] = json_encode($validated['coating_data']);
        }

        $coating->update($validated);

        Log::info('Manually patched coating serial', ['serial' => $coating->serial]);

        return response()->json($coating);
    }

    public function destroy(Coating $coating)
    {
        $coating->delete();

        return response()->json(null, 204);
    }
}
