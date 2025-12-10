<?php

namespace App\Http\Controllers;

use App\Models\ExcessLayers;
use Illuminate\Http\Request;

class ExcessLayersController extends Controller
{
    /**
     * Display all Excess Layer records.
     */
    public function index()
    {
        return response()->json(ExcessLayers::all(), 200);
    }

    /**
     * Store a new Excess Layer record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'furnace'         => 'required|string|max:255',
            'mass_prod'       => 'required|string|max:255',
            'layer'           => 'required|integer|min:1',
            'layer_data'      => 'nullable|json',
            'available_boxes' => 'nullable|integer|min:0',
        ]);

        $excessLayer = ExcessLayers::create($validated);

        return response()->json($excessLayer, 201);
    }

    /**
     * Display a specific Excess Layer record.
     */
    public function show($id)
    {
        $excessLayer = ExcessLayers::findOrFail($id);
        return response()->json($excessLayer, 200);
    }

    /**
     * Update an existing Excess Layer record.
     */
    public function update(Request $request, $id)
    {
        $excessLayer = ExcessLayers::findOrFail($id);

        $validated = $request->validate([
            'furnace'         => 'required|string|max:255',
            'mass_prod'       => 'required|string|max:255',
            'layer'           => 'required|integer|min:1',
            'layer_data'      => 'nullable|json',
            'available_boxes' => 'nullable|integer|min:0',
        ]);

        $excessLayer->update($validated);

        return response()->json($excessLayer, 200);
    }

    /**
     * Delete an Excess Layer record.
     */
    public function destroy($id)
    {
        $excessLayer = ExcessLayers::findOrFail($id);
        $excessLayer->delete();

        return response()->json(['message' => 'Record deleted successfully.'], 204);
    }

    public function mergeExcessLayer(Request $request)
    {
        $validated = $request->validate([
            'furnace' => 'required|string',
            'mass_prod' => 'required|string',
            'layer' => 'required|integer',
            'layer_data' => 'required|array', // array, not JSON
        ]);

        $newData = $validated['layer_data'];

        $existingLayer = ExcessLayers::where([
            'furnace' => $validated['furnace'],
            'mass_prod' => $validated['mass_prod'],
            'layer' => $validated['layer'],
        ])->first();

        if ($existingLayer) {
            $existingData = $existingLayer->layer_data; // array
            $mergedData = [];

            foreach ($existingData as $row) {
                $mergedData[$row['rowTitle']] = $row['data'];
            }

            foreach ($newData as $row) {
                $title = $row['rowTitle'];
                $mergedData[$title] = isset($mergedData[$title])
                    ? array_merge($mergedData[$title], $row['data'])
                    : $row['data'];
            }

            $finalData = [];
            foreach ($mergedData as $rowTitle => $boxes) {
                $finalData[] = [
                    'rowTitle' => $rowTitle,
                    'data' => $boxes
                ];
            }

            $existingLayer->layer_data = $finalData;
            $existingLayer->available_boxes = count($finalData);
            $existingLayer->save();

            return response()->json($existingLayer, 200);
        }

        // No existing layer: create
        $newRecord = ExcessLayers::create([
            'furnace' => $validated['furnace'],
            'mass_prod' => $validated['mass_prod'],
            'layer' => $validated['layer'],
            'layer_data' => $newData,
            'available_boxes' => count($newData),
        ]);

        return response()->json($newRecord, 201);
    }

    public function existingExcessLayerList($furnace, $massprod)
    {
        $excess = ExcessLayers::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer'); // returns [2,4,5]

        return response()->json([
            'excess_layers' => $excess
        ]);
    }


}
