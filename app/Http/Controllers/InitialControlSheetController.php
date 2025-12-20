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
            'total_boxes' => 'nullable|integer',
        ]);

        $sheet = InitialControlSheet::create([
            'model_name'  => $validated['model_name'],
            'lot_no'      => $validated['lot_no'],
            'layer_data'  => $validated['layer_data'] ?? null,
            'excess_data' => $validated['excess_data'] ?? null,
            'total_boxes' => $validated['total_boxes'],
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
            'total_boxes' => 'sometimes|integer',
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
            'lot_no'     => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ]);

        // Check based only on lot_no
        $lotExists = InitialControlSheet::where('lot_no', $request->lot_no)->exists();

        // Check based on the pair (model_name + lot_no)
        $pairExists = InitialControlSheet::where('lot_no', $request->lot_no)
            ->where('model_name', $request->model_name)
            ->exists();

        return response()->json([
            'duplicate_lot'  => $lotExists,
            'duplicate_pair' => $pairExists,
        ]);
    }

    public function fetchAllLotNumbers()
    {
        $records = InitialControlSheet::whereNotNull('lot_no')
            ->orderBy('created_at', 'desc') // newest first
            ->get();

        return response()->json($records);
    }

    public function fetchAllLotModels(Request $request)
    {
        $validated = $request->validate([
            'lot_no' => 'required|string|max:50',
        ]);

        $records = InitialControlSheet::where('lot_no', $validated['lot_no'])
            ->whereNotNull('model_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($records);
    }


    public function fetchLotData(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:100',
            'lot_no'     => 'required|string|max:50',
        ]);

        $sheet = InitialControlSheet::where('model_name', $validated['model_name'])
            ->where('lot_no', $validated['lot_no'])
            ->first();

        return response()->json([
            'data' => $sheet
        ]);
    }

    public function fetchTotalBoxes(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:100',
            'lot_no'     => 'required|string|max:50',
        ]);

        $record = InitialControlSheet::where('model_name', $validated['model_name'])
            ->where('lot_no', $validated['lot_no'])
            ->select('total_boxes')
            ->first();

        return response()->json([
            'total_boxes' => $record?->total_boxes ?? null,
        ]);
    }

    public function validateLayers(Request $request)
    {
        $validated = $request->validate([
            'model_name'   => 'required|string|max:100',
            'lot_no'       => 'required|string|max:50',
            'main_count'   => 'required|integer|min:0',
            'excess_count' => 'required|integer|min:0',
        ]);

        $record = InitialControlSheet::where('model_name', $validated['model_name'])
            ->where('lot_no', $validated['lot_no'])
            ->select('layer_data', 'excess_data')
            ->first();

        if (!$record) {
            return response()->json([
                'validated' => false,
                'message'   => 'Record not found.',
            ], 404);
        }

        $layerData  = is_array($record->layer_data)
            ? $record->layer_data
            : json_decode($record->layer_data, true);

        $excessData = is_array($record->excess_data)
            ? $record->excess_data
            : json_decode($record->excess_data, true);

        $layerCount = 0;
        if (!empty($layerData[0]['data']) && is_array($layerData[0]['data'])) {
            $layerCount = count($layerData[0]['data']);
        }

        $excessBoxCount = 0;
        if (!empty($excessData[0]['data']) && is_array($excessData[0]['data'])) {
            $excessBoxCount = count($excessData[0]['data']);
        }

        $isValid =
            $validated['main_count'] === $layerCount &&
            $validated['excess_count'] === $excessBoxCount;

        return response()->json([
            'validated'    => $isValid,
            'layer_count'  => $layerCount,
            'excess_count' => $excessBoxCount,
        ]);
    }

    public function fetchLayerExcessData(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:100',
            'lot_no'     => 'required|string|max:50',
        ]);

        $record = InitialControlSheet::where('model_name', $validated['model_name'])
            ->where('lot_no', $validated['lot_no'])
            ->select('layer_data', 'excess_data', 'total_boxes')
            ->first();

        if (!$record) {
            return response()->json([
                'layer_data'  => [],
                'excess_data' => [],
                'total_boxes' => 0,
                'message'     => 'Record not found.',
            ], 404);
        }

        return response()->json([
            'layer_data'  => is_array($record->layer_data)
                ? $record->layer_data
                : json_decode($record->layer_data, true) ?? [],

            'excess_data' => is_array($record->excess_data)
                ? $record->excess_data
                : json_decode($record->excess_data, true) ?? [],

            'total_boxes' => $record->total_boxes ?? 0,
        ]);
    }


}
