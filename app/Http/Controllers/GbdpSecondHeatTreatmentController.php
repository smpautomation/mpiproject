<?php

namespace App\Http\Controllers;

use App\Models\GbdpSecondHeatTreatment;
use Illuminate\Http\Request;

class GbdpSecondHeatTreatmentController extends Controller
{
    public function index()
    {
        return GbdpSecondHeatTreatment::all()->map(function ($item) {
            $item->gbdp_1st = json_decode($item->gbdp_1st, true);
            $item->gbdp_2nd = json_decode($item->gbdp_2nd, true);
            return $item;
        });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'furnace' => 'nullable|string|max:255',
            'mass_prod' => 'nullable|string|max:255',
            'layer'     => 'nullable|numeric',
            'gbdp_1st'  => 'nullable|array',
            'gbdp_2nd'  => 'nullable|array',
        ]);

        $validated['gbdp_1st'] = json_encode($validated['gbdp_1st'] ?? []);
        $validated['gbdp_2nd'] = json_encode($validated['gbdp_2nd'] ?? []);

        return GbdpSecondHeatTreatment::create($validated);
    }

    public function show($id)
    {
        $item = GbdpSecondHeatTreatment::findOrFail($id);
        $item->gbdp_1st = json_decode($item->gbdp_1st, true);
        $item->gbdp_2nd = json_decode($item->gbdp_2nd, true);

        return $item;
    }

    public function update(Request $request, $id)
    {
        $item = GbdpSecondHeatTreatment::findOrFail($id);

        $validated = $request->validate([
            'mass_prod' => 'sometimes|required|string|max:255',
            'layer'     => 'nullable|numeric',
            'gbdp_1st'  => 'nullable|array',
            'gbdp_2nd'  => 'nullable|array',
        ]);

        if (isset($validated['gbdp_1st'])) {
            $validated['gbdp_1st'] = json_encode($validated['gbdp_1st']);
        }

        if (isset($validated['gbdp_2nd'])) {
            $validated['gbdp_2nd'] = json_encode($validated['gbdp_2nd']);
        }

        $item->update($validated);

        return $item;
    }

    public function destroy($id)
    {
        $item = GbdpSecondHeatTreatment::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function getLayersByMassProd($massProd)
    {
        $layers = GbdpSecondHeatTreatment::where('mass_prod', $massProd)
            ->pluck('layer');

        return response()->json(['layers' => $layers], 200);
    }

    public function getLayerData($furnace, $massprod, $layer)
    {
        try {
            $data = GbdpSecondHeatTreatment::where('furnace', $furnace)
                ->where('mass_prod', $massprod)
                ->where('layer', $layer)
                ->first();

            if (!$data) {
                return response()->json([
                    'message' => "No data found for Furnace: {$furnace}, Mass Production: {$massprod}, Layer: {$layer}"
                ], 404);
            }

            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error while fetching layer data',
                'details' => $e->getMessage()
            ], 500);
        }
    }

}
