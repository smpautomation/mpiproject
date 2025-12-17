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


    public function fetchLotData($modelName, $lotNo)
    {
        $sheet = InitialControlSheet::where('model_name', $modelName)
            ->where('lot_no', $lotNo)
            ->first();

        return response()->json([
            'data'  => $sheet
        ]);
    }

    public function fetchTotalBoxes($modelName, $lotNo)
    {
        $record = InitialControlSheet::where('model_name', $modelName)
            ->where('lot_no', $lotNo)
            ->select('total_boxes')
            ->first();

        return response()->json([
            'total_boxes' => $record ? $record->total_boxes : null
        ]);
    }

    public function validateLayers($modelName, $lotNo, $mainCount, $excessCount)
    {
        $record = InitialControlSheet::where('model_name', $modelName)
            ->where('lot_no', $lotNo)
            ->select('layer_data', 'excess_data')
            ->first();

        if (!$record) {
            return response()->json([
                'validated' => false,
                'message' => 'Record not found.'
            ]);
        }

        // Ensure layer_data and excess_data are arrays
        $layerData  = is_array($record->layer_data) ? $record->layer_data : json_decode($record->layer_data, true);
        $excessData = is_array($record->excess_data) ? $record->excess_data : json_decode($record->excess_data, true);

        // Safely extract number of boxes in main layer
        $layerCount = 0;
        if (!empty($layerData) && isset($layerData[0]['data']) && is_array($layerData[0]['data'])) {
            $layerCount = count($layerData[0]['data']);
        }

        // Safely extract number of boxes in excess layer
        $excessBoxCount = 0;
        if (!empty($excessData) && isset($excessData[0]['data']) && is_array($excessData[0]['data'])) {
            $excessBoxCount = count($excessData[0]['data']);
        }

        // Compare counts with user-provided values
        $validated = (intval($mainCount) === $layerCount) && (intval($excessCount) === $excessBoxCount);

        return response()->json([
            'validated'    => $validated,
            'layer_count'  => $layerCount,
            'excess_count' => $excessBoxCount
        ]);
    }

    public function fetchLayerExcessData($modelName, $lotNo)
    {
        $record = InitialControlSheet::where('model_name', $modelName)
            ->where('lot_no', $lotNo)
            ->select('layer_data', 'excess_data', 'total_boxes')
            ->first();

        if (!$record) {
            return response()->json([
                'layer_data'  => [],
                'excess_data' => [],
                'total_boxes' => 0,
                'message'     => 'Record not found.'
            ]);
        }

        $layerData  = is_array($record->layer_data) ? $record->layer_data : json_decode($record->layer_data, true);
        $excessData = is_array($record->excess_data) ? $record->excess_data : json_decode($record->excess_data, true);

        return response()->json([
            'layer_data'  => $layerData ?? [],
            'excess_data' => $excessData ?? [],
            'total_boxes' => $record->total_boxes ?? 0
        ]);
    }


}
