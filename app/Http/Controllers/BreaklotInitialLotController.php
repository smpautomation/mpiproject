<?php

namespace App\Http\Controllers;

use App\Models\BreaklotCoating;
use App\Models\BreaklotInitialLot;
use App\Models\BreaklotSecondCoating;
use App\Models\BreaklotFilmpasting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BreaklotInitialLotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreaklotInitialLot::orderBy('created_at', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod'   => 'nullable|string',
            'furnace'     => 'nullable|string',
            'layer'       => 'nullable|string',
            'initial_lot' => 'nullable|string',
            'initial_model' => 'nullable|string',
        ]);

        $record = BreaklotInitialLot::create($validated);

        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BreaklotInitialLot $breaklotInitialLot)
    {
        return response()->json($breaklotInitialLot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreaklotInitialLot $breaklotInitialLot)
    {
        $validated = $request->validate([
            'mass_prod'   => 'sometimes|nullable|string',
            'furnace'     => 'sometimes|nullable|string',
            'layer'       => 'sometimes|nullable|string',
            'initial_lot' => 'sometimes|nullable|string',
            'initial_model' => 'sometimes|nullable|string',
        ]);

        $breaklotInitialLot->update($validated);

        Log::info('Updated breaklot initial lot', [
            'id' => $breaklotInitialLot->id
        ]);

        return response()->json($breaklotInitialLot);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreaklotInitialLot $breaklotInitialLot)
    {
        $breaklotInitialLot->delete();

        return response()->json(null, 204);
    }

    public function exists(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
            'layer'     => 'required|string',
        ]);

        $row = BreaklotInitialLot::where($validated)->first();

        return response()->json([
            'exists'       => (bool) $row,
            'initial_lot'  => $row?->initial_lot, // null if not found
        ]);
    }

    public function showAllAdditional(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
            'layer'     => 'required|string',
        ]);

        // --- Set A : Initial Lot ---
        $initialLot = BreaklotInitialLot::where($validated)->first();

        $sets = [];

        if ($initialLot) {
            $sets['A'] = [
                'type'        => 'initial',
                'initial_lot' => $initialLot->initial_lot,
                'initial_model' => $initialLot->initial_model,
            ];
        }

        // --- Fetch all layer-based records ---
        $records = collect()
            ->merge(
                BreaklotCoating::where($validated)
                    ->get(['layer_code', 'model', 'lot_no'])
            )
            ->merge(
                BreaklotSecondCoating::where($validated)
                    ->get(['layer_code', 'model', 'lot_no'])
            )
            ->merge(
                BreaklotFilmpasting::where($validated)
                    ->get(['layer_code', 'model', 'lot_no'])
            );

        // --- Sort by last letter of layer_code ---
        $records = $records->sortBy(function ($row) {
            return substr($row->layer_code, -1);
        });

        // --- Assign Sets B, C, D... ---
        foreach ($records as $row) {
            $setKey = substr($row->layer_code, -1); // B, C, D...

            $sets[$setKey] = [
                'type'       => 'additional',
                'layer_code' => $row->layer_code,
                'model'      => $row->model,
                'lot_no'     => $row->lot_no,
            ];
        }

        return response()->json([
            'exists' => !empty($sets),
            'sets'   => $sets,
        ]);
    }


}
