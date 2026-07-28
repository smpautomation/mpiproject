<?php

namespace App\Http\Controllers;

use App\Models\SecondGbdpModels;
use Illuminate\Http\Request;

class SecondGbdpModelsController extends Controller
{
    public function index()
    {
        return SecondGbdpModels::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
            'encoded_by' => 'nullable|string|max:255',
        ]);

        return SecondGbdpModels::create($validated);
    }

    public function show($id)
    {
        return SecondGbdpModels::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $secondGBDP = SecondGbdpModels::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
            'encoded_by' => 'nullable|string|max:255',
        ]);

        $secondGBDP->update($validated);

        return $secondGBDP;
    }

    public function destroy($id)
    {
        $secondGBDP = SecondGbdpModels::findOrFail($id);
        $secondGBDP->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function checkExisting(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
        ]);

        $exists = SecondGbdpModels::where('model_name', $validated['model_name'])->exists();

        return response()->json([
            'exists' => $exists
        ]);
    }

    public function updateModelName(Request $request)
    {
        $validated = $request->validate([
            'old_model_name' => 'required|string|max:255',
            'new_model_name' => 'required|string|max:255',
            'encoded_by'     => 'nullable|string|max:255',
        ]);

        $oldName = trim($validated['old_model_name']);
        $newName = trim($validated['new_model_name']);
        $encodedBy = $validated['encoded_by'] ?? 'SYSTEM';

        $isNewGbdp = str_contains(strtoupper($newName), '2ND GBDP');

        // 1. Locate existing record by old name
        $existingOldRecord = SecondGbdpModels::where('model_name', $oldName)->first();

        // 2. Check if a record with the NEW name ALREADY exists in the table
        $existingNewRecord = SecondGbdpModels::where('model_name', $newName)->first();

        // CASE A: User changed name to a NON-GBDP model (e.g., "2ND GBDP MODEL A" -> "STANDARD MODEL")
        if (!$isNewGbdp) {
            if ($existingOldRecord) {
                // Delete the old record since it's no longer a GBDP model
                $existingOldRecord->delete();
                return response()->json(['message' => 'Old GBDP record removed since model is no longer 2ND GBDP']);
            }
            return response()->json(['message' => 'No action needed']);
        }

        // CASE B: Target new name ALREADY exists in second_gbdp_models
        if ($existingNewRecord) {
            // If the old record exists and isn't the same row, delete the redundant old one
            if ($existingOldRecord && $existingOldRecord->id !== $existingNewRecord->id) {
                $existingOldRecord->delete();
            }

            // Just ensure encoded_by is updated on the target record
            $existingNewRecord->update(['encoded_by' => $encodedBy]);

            return response()->json([
                'message' => 'Target model name already existed in GBDP records. Synced successfully without duplicating.',
                'data'    => $existingNewRecord
            ]);
        }

        // CASE C: Old record exists -> Rename it to new_model_name
        if ($existingOldRecord) {
            $existingOldRecord->update([
                'model_name' => $newName,
                'encoded_by' => $encodedBy,
            ]);

            return response()->json([
                'message' => 'GBDP model renamed successfully',
                'data'    => $existingOldRecord
            ]);
        }

        // CASE D: Neither old nor new existed, but new_model_name is a GBDP model -> Create fresh
        $newRecord = SecondGbdpModels::create([
            'model_name' => $newName,
            'encoded_by' => $encodedBy,
        ]);

        return response()->json([
            'message' => 'New GBDP model record created',
            'data'    => $newRecord
        ]);
    }
}
