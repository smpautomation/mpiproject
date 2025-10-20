<?php

namespace App\Http\Controllers;

use App\Models\GbdpSecondCoating;
use Illuminate\Http\Request;

class GbdpSecondCoatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(GbdpSecondCoating::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|numeric',
            'coating_info_1stgbdp' => 'required|array',
            'coating_info_2ndgbdp' => 'required|array',
            'coating_data_1stgbdp' => 'required|array',
            'coating_data_2ndgbdp' => 'required|array',
        ]);

        $coating = GbdpSecondCoating::create($validated);

        return response()->json($coating, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GbdpSecondCoating $gbdpSecondCoating)
    {
        return response()->json($gbdpSecondCoating, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GbdpSecondCoating $gbdpSecondCoating)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|numeric',
            'coating_info_1stgbdp' => 'nullable|array',
            'coating_info_2ndgbdp' => 'nullable|array',
            'coating_data_1stgbdp' => 'nullable|array',
            'coating_data_2ndgbdp' => 'nullable|array',
        ]);

        $gbdpSecondCoating->update($validated);

        return response()->json($gbdpSecondCoating, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GbdpSecondCoating $gbdpSecondCoating)
    {
        $gbdpSecondCoating->delete();

        return response()->json(null, 204);
    }

    /**
     * Custom: Get all layers for a given mass_prod.
     */
    public function getLayersByMassProd($massProd)
    {
        // Fetch all layer values for this mass_prod
        $layers = GbdpSecondCoating::where('mass_prod', $massProd)
            ->pluck('layer')  // get only the "layer" column
            ->filter()         // remove nulls
            ->map(fn($layer) => (string)$layer) // cast to string if needed
            ->toArray();

        return response()->json([
            'completed_layers' => $layers,
        ]);
    }

    public function getLayerData($massprod, $layer)
    {
        try {
            $data = GbdpSecondCoating::where('mass_prod', $massprod)
                ->where('layer', $layer)
                ->first();

            if (!$data) {
                return response()->json([
                    'message' => "No coating data found for Mass Production: {$massprod}, Layer: {$layer}"
                ], 404);
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error while fetching coating layer data',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
