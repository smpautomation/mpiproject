<?php

namespace App\Http\Controllers;

use App\Models\GbdpSecondHeatTreatment;
use App\Models\BreaklotSecondHeatTreatment;
use App\Models\BreaklotInitialLotHt;
use App\Models\MassProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $validated = $request->validate([
            'furnace'   => 'required|string|max:255',
            'mass_prod' => 'required|string|max:255',
            'layer'     => 'required',

            'model'     => 'sometimes|nullable|string|max:255',
            'lot_no'    => 'sometimes|nullable|string|max:255',
        ]);

        $furnace  = $validated['furnace'];
        $massProd = $validated['mass_prod'];
        $layer    = trim($validated['layer']);

        $normalizedLayer = str_replace('.', '_', $layer);
        $column = 'layer_' . $normalizedLayer;

        $prod = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->first();

        if (!$prod || empty($prod->$column)) {
            return response()->json([
                'is_breaklot'   => false,
                'is_existing'   => false,
                'do_not_proceed'=> false,
            ]);
        }

        $layerData = json_decode($prod->$column, true);

        if (!is_array($layerData)) {
            return response()->json([
                'is_breaklot'   => false,
                'is_existing'   => false,
                'do_not_proceed'=> false,
            ]);
        }

        $modelRow = [];
        $lotRow   = [];

        foreach ($layerData as $row) {
            if (($row['rowTitle'] ?? null) === 'MODEL:') {
                $modelRow = $row['data'] ?? [];
            }

            if (($row['rowTitle'] ?? null) === 'LT. No.:') {
                $lotRow = $row['data'] ?? [];
            }
        }

        $pairs = [];

        foreach ($modelRow as $box => $model) {
            $lot = $lotRow[$box] ?? null;

            $model = trim((string) $model);
            $lot   = trim((string) $lot);

            if ($model !== '' && $lot !== '') {
                $pairs["{$model}|{$lot}"] = true;
            }
        }

        $isBreaklot = count($pairs) > 1;

        $inputModel = $validated['model'] ?? null;
        $inputLot   = $validated['lot_no'] ?? null;

        $existsInBreaklotSecond = false;

        if ($inputModel && $inputLot) {
            $existsInBreaklotSecond = BreaklotSecondHeatTreatment::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer', $layer)
                ->where('model', $inputModel)
                ->where('lot_no', $inputLot)
                ->exists();
        }

        $existsInInitialLot = BreaklotInitialLotHt::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->where('layer', $layer)
            ->where('model', $inputModel)
            ->where('lot_no', $inputLot)
            ->exists();

        $existsInSecondGbdpHt = GbdpSecondHeatTreatment::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->where('layer', $layer)
            ->exists();

        $isExisting = $existsInBreaklotSecond || $existsInSecondGbdpHt;

        // NEW RULE: validate pair existence in derived dataset
        $doNotProceed = false;

        if ($inputModel && $inputLot) {
            $key = "{$inputModel}|{$inputLot}";

            if (!isset($pairs[$key])) {
                $doNotProceed = true;
            }
        }

        return response()->json([
            'is_breaklot'       => $isBreaklot,
            'is_existing'       => $isExisting,
            'is_initial_lot'    => $existsInInitialLot,
            'do_not_proceed'    => $doNotProceed,
        ]);
    }

}
