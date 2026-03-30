<?php

namespace App\Http\Controllers;

use App\Models\GbdpSecondHeatTreatment;
use App\Models\BreaklotSecondHeatTreatment;
use App\Models\BreaklotInitialLotHt;
use App\Models\MassProduction;
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
            'furnace' => 'sometimes|string|max:255',
            'mass_prod' => 'sometimes|string|max:255',
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

    public function getLayersByMassProd($furnace, $massProd)
    {
        $layers = GbdpSecondHeatTreatment::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
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

    public function checkBreaklot(Request $request)
    {
        $request->validate([
            'furnace'   => 'required|string|max:255',
            'mass_prod' => 'required|string|max:255',
            'layer'     => 'required',
            'model'     => 'required|string|max:255',
            'lot_no'    => 'required|string|max:255'
        ]);

        $layerRaw = (string) $request->layer;

        // 1. BreaklotSecond (strict)
        $existsInBreaklotSecond = BreaklotSecondHeatTreatment::where('furnace', $request->furnace)
            ->where('mass_prod', $request->mass_prod)
            ->where('layer', $layerRaw)
            ->where('model', $request->model)
            ->where('lot_no', $request->lot_no)
            ->exists();

        // BreaklotInitial (strict)
        $existsInInitial = BreaklotInitialLotHt::where('furnace', $request->furnace)
            ->where('mass_prod', $request->mass_prod)
            ->where('layer', $layerRaw)
            ->where('initial_model', $request->model)
            ->where('initial_lot', $request->lot_no)
            ->exists();

        // GBDP Second HT
        $existsInSecondGbdpHt = GbdpSecondHeatTreatment::where('furnace', $request->furnace)
            ->where('mass_prod', $request->mass_prod)
            ->where('layer', $layerRaw)
            ->exists();

        // FINAL
        $isBreaklot = $existsInInitial;
        $isBreaklot2ndgbdp = $existsInInitial && ($existsInBreaklotSecond || $existsInSecondGbdpHt);
        $isExisting = $existsInBreaklotSecond || $existsInSecondGbdpHt;

        return response()->json([
            'is_breaklot' => $isBreaklot,
            'is_breaklot_2ndgbdp' => $isBreaklot2ndgbdp,
            'is_existing' => $isExisting,
        ]);
    }
}
