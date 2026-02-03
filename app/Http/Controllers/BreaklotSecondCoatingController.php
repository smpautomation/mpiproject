<?php

namespace App\Http\Controllers;

use App\Models\BreaklotSecondCoating;
use Illuminate\Http\Request;

class BreaklotSecondCoatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreaklotSecondCoating::orderBy('id', 'desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod'              => 'nullable|string',
            'furnace'                => 'nullable|string',
            'layer'                  => 'nullable|string',
            'layer_code'             => 'nullable|string',
            'model'                  => 'nullable|string',
            'lot_no'                 => 'nullable|string',

            'coating_info_1stgbdp'   => 'nullable|array',
            'coating_info_2ndgbdp'   => 'nullable|array',
            'coating_data_1stgbdp'   => 'nullable|array',
            'coating_data_2ndgbdp'   => 'nullable|array',
        ]);

        $record = BreaklotSecondCoating::create($validated);

        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BreaklotSecondCoating $breaklotSecondCoating)
    {
        return response()->json($breaklotSecondCoating);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BreaklotSecondCoating $breaklotSecondCoating)
    {

        $validated = $request->validate([
            'mass_prod'              => 'nullable|string',
            'furnace'                => 'nullable|string',
            'layer'                  => 'nullable|string',
            'layer_code'             => 'nullable|string',
            'model'                  => 'nullable|string',
            'lot_no'                 => 'nullable|string',
            'coating_info_1stgbdp' => 'nullable|array',
            'coating_info_2ndgbdp' => 'nullable|array',
            'coating_data_1stgbdp' => 'nullable|array',
            'coating_data_2ndgbdp' => 'nullable|array',
        ]);

        $breaklotSecondCoating->update($validated);

       return response()->json($breaklotSecondCoating, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreaklotSecondCoating $breaklotSecondCoating)
    {
        $breaklotSecondCoating->delete();
        return response()->json(null, 204);
    }


}
