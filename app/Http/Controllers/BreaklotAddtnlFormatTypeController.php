<?php

namespace App\Http\Controllers;

use App\Models\BreaklotAddtnlFormatType;
use Illuminate\Http\Request;

class BreaklotAddtnlFormatTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreaklotAddtnlFormatType::orderBy('created_at', 'desc')->get();
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
            'model'       => 'nullable|string',
            'lot_no'      => 'nullable|string',
            'format_type' => 'nullable|string',
        ]);

        $record = BreaklotAddtnlFormatType::create($validated);

        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BreaklotAddtnlFormatType $breaklotAddtnlFormatType)
    {
        return response()->json($breaklotAddtnlFormatType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreaklotAddtnlFormatType $breaklotAddtnlFormatType)
    {
        $validated = $request->validate([
            'mass_prod'   => 'nullable|string',
            'furnace'     => 'nullable|string',
            'layer'       => 'nullable|string',
            'model'       => 'nullable|string',
            'lot_no'      => 'nullable|string',
            'format_type' => 'nullable|string',
        ]);

        $breaklotAddtnlFormatType->update($validated);

        return response()->json($breaklotAddtnlFormatType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreaklotAddtnlFormatType $breaklotAddtnlFormatType)
    {
        $breaklotAddtnlFormatType->delete();

        return response()->json(null, 204);
    }
}
