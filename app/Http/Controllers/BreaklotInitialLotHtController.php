<?php

namespace App\Http\Controllers;

use App\Models\BreaklotInitialLotHt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BreaklotInitialLotHtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreaklotInitialLotHt::orderBy('created_at', 'desc')->get();
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

        $record = BreaklotInitialLotHt::create($validated);

        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BreaklotInitialLotHt $breaklotInitialLot)
    {
        return response()->json($breaklotInitialLot);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreaklotInitialLotHt $breaklotInitialLotHt)
    {
        $validated = $request->validate([
            'mass_prod'   => 'sometimes|nullable|string',
            'furnace'     => 'sometimes|nullable|string',
            'layer'       => 'sometimes|nullable|string',
            'initial_lot' => 'sometimes|nullable|string',
            'initial_model' => 'sometimes|nullable|string',
        ]);

        $breaklotInitialLotHt->update($validated);

        Log::info('Updated breaklot initial lot', [
            'id' => $breaklotInitialLotHt->id
        ]);

        return response()->json($breaklotInitialLotHt);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreaklotInitialLotHt $breaklotInitialLotHt)
    {
        $breaklotInitialLotHt->delete();

        return response()->json(null, 204);
    }

    public function exists(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
            'layer'     => 'required|string',
        ]);

        $row = BreaklotInitialLotHt::where($validated)->first();

        return response()->json([
            'exists'       => (bool) $row,
            'initial_lot'  => $row?->initial_lot, // null if not found
            'initial_model' => $row?->initial_model
        ]);
    }
}
