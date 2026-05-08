<?php

namespace App\Http\Controllers;

use App\Models\BreaklotCoating;
use App\Models\BreaklotSecondCoating;
use App\Models\Coating;
use App\Models\CoatingPending;
use App\Models\GbdpSecondCoating;
use App\Models\BreaklotInitialLotHt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoatingController extends Controller
{
    public function index()
    {
        return Coating::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'furnace' => 'nullable|string',
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'slurry_lot_no' => 'nullable|string',
            'loader_operator' => 'nullable|string',
            'min_tb_content' => 'nullable|string',
            'sample_qty' => 'nullable|string',
            'total_magnet_weight' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'coating_data' => 'nullable|array',
            'checker_operator' => 'nullable|string',
            'time_start' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finish' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'maximum' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'average' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);

        $coating = Coating::create([
            ...$validated,
            'coating_data' => json_encode($validated['coating_data']),
        ]);

        return response()->json($coating, 201);
    }

    public function show(Coating $coating)
    {
        return response()->json($coating);
    }

    public function update(Request $request, $serial)
    {
        $coating = Coating::where('serial', $serial)->firstOrFail();

        $validated = $request->validate([
            'date' => 'sometimes|nullable|date',
            'furnace' => 'sometimes"nullable|string',
            'mass_prod' => 'sometimes|string',
            'layer' => 'sometimes|string',
            'machine_no' => 'sometimes|nullable|string',
            'slurry_lot_no' => 'sometimes|nullable|string',
            'loader_operator' => 'sometimes|nullable|string',
            'min_tb_content' => 'sometimes|nullable|string',
            'sample_qty' => 'sometimes|nullable|string',
            'total_magnet_weight' => 'sometimes|nullable|string',
            'unloader_operator' => 'sometimes|nullable|string',
            'coating_data' => 'sometimes|nullable|array',
            'checker_operator' => 'sometimes|nullable|string',
            'time_start' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finish' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'maximum' => 'sometimes|nullable|numeric',
            'minimum' => 'sometimes|nullable|numeric',
            'average' => 'sometimes|nullable|numeric',
            'remarks' => 'sometimes|nullable|string',
        ]);

        if (isset($validated['coating_data'])) {
            $validated['coating_data'] = json_encode($validated['coating_data']);
        }

        $coating->update($validated);

        //Log::info('Manually patched coating serial', ['serial' => $coating->serial]);

        return response()->json($coating);
    }

    public function destroy(Coating $coating)
    {
        $coating->delete();

        return response()->json(null, 204);
    }

    public function checkExisting(Request $request)
    {
        $request->validate([
            'furnace'   => 'required|string', // added furnace
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
        ]);

        $exists = Coating::where('furnace', $request->furnace) // include furnace
            ->where('mass_prod', $request->mass_prod)
            ->where('layer', $request->layer)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function checkPending(Request $request)
    {
        $request->validate([
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
        ]);

        $pending = CoatingPending::where('mass_prod', $request->mass_prod)
            ->where('layer', $request->layer)
            ->exists();

        return response()->json(['pending' => $pending]);
    }

    public function addPending(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'layer'     => 'required|numeric',
        ]);

        // Avoid duplicates
        $pending = CoatingPending::firstOrCreate([
            'mass_prod' => $validated['mass_prod'],
            'layer'     => $validated['layer'],
        ]);

        return response()->json($pending, 201);
    }

    public function removePending(Request $request)
    {
        $request->validate([
            'mass_prod' => 'required|string',
            'layer'     => 'required|numeric',
        ]);

        CoatingPending::where('mass_prod', $request->mass_prod)
            ->where('layer', $request->layer)
            ->delete();

        return response()->json(['message' => 'Pending cleared'], 200);
    }

    public function getCoatingData(Request $request)
    {
        $request->validate([
            'furnace' => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|numeric',
        ]);

        $coating = Coating::where('furnace', $request->furnace) // added furnace filter
            ->where('mass_prod', $request->mass_prod)
            ->where('layer', $request->layer)
            ->first();

        if (!$coating) {
            return response()->json([
                'message' => "Coating record not found for Furnace: {$request->furnace}, Mass Production: {$request->mass_prod}, Layer: {$request->layer}."
            ], 404);
        }

        return response()->json($coating);
    }

    public function getCoatingDataBreaklot(Request $request)
    {
        $request->validate([
            'furnace' => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|numeric',
            'lot_no' => 'required|string',
        ]);

        $coating = BreaklotCoating::where('furnace', $request->furnace)
            ->where('mass_prod', $request->mass_prod)
            ->where('layer', $request->layer)
            ->where('lot_no', $request->lot_no)
            ->first();

        // fallback
        if (!$coating) {
            $coating = BreaklotSecondCoating::where('furnace', $request->furnace)
                ->where('mass_prod', $request->mass_prod)
                ->where('layer', $request->layer)
                ->where('lot_no', $request->lot_no)
                ->first();
        }

        if (!$coating) {
            return response()->json([
                'message' => "Coating record not found for Furnace: {$request->furnace}, Mass Production: {$request->mass_prod}, Layer: {$request->layer}."
            ], 404);
        }

        return response()->json($coating);
    }

    public function getLayersByMassProd($furnace, $massProd)
    {
        // Fetch all layer values for this furnace + mass_prod
        $layers = Coating::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->pluck('layer')               // get only the "layer" column
            ->filter()                     // remove nulls
            ->map(fn($layer) => (string)$layer) // cast to string if needed
            ->toArray();

        return response()->json([
            'completed_layers' => $layers,
        ]);
    }

    public function getCoatingRemarks(Request $request)
    {
        $request->validate([
            'furnace'   => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
            'model'     => 'required|string',
            'lot_no'    => 'required|string',
        ]);

        $massProd = $request->mass_prod;
        $furnace  = $request->furnace;
        $layer    = $request->layer;
        $model    = $request->model;
        $lotNo    = $request->lot_no;

        $response = [
            'found_coating_remarks' => false,
            'remarks' => null,
        ];

        $matchesLongAging = function ($text) {
            return is_string($text) && stripos($text, 'long aging') !== false;
        };

        $safeJsonRemarks = function ($json) {
            if (empty($json) || !is_string($json)) {
                return null;
            }

            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return null;
            }

            return $data['remarks'] ?? null;
        };

        // 1. Gate check
        $initialLot = BreaklotInitialLotHt::where([
            'mass_prod'      => $massProd,
            'furnace'        => $furnace,
            'layer'          => $layer,
            'initial_model'  => $model,
            'initial_lot'    => $lotNo,
        ])->first();

        if ($initialLot) {

            // 2. Coating (highest priority)
            $coating = Coating::where([
                'mass_prod' => $massProd,
                'furnace'   => $furnace,
                'layer'     => $layer,
            ])->first();

            if ($coating && $matchesLongAging($coating->remarks)) {
                return response()->json([
                    'found_coating_remarks' => true,
                    'remarks' => $coating->remarks,
                ]);
            }

            // 3. GBDP second coating (JSON safe)
            $gbdp = GbdpSecondCoating::where([
                'mass_prod' => $massProd,
                'furnace'   => $furnace,
                'layer'     => $layer,
            ])->first();

            $gbdpRemarks = $safeJsonRemarks($gbdp->coating_info_2ndgbdp ?? null);

            if ($matchesLongAging($gbdpRemarks)) {
                return response()->json([
                    'found_coating_remarks' => true,
                    'remarks' => $gbdpRemarks,
                ]);
            }

            return response()->json($response);
        }

        // 4. BreaklotCoating fallback
        $breaklot = BreaklotCoating::where([
            'mass_prod' => $massProd,
            'furnace'   => $furnace,
            'layer'     => $layer,
            'model'     => $model,
            'lot_no'    => $lotNo,
        ])->first();

        if ($breaklot && $matchesLongAging($breaklot->remarks)) {
            return response()->json([
                'found_coating_remarks' => true,
                'remarks' => $breaklot->remarks,
            ]);
        }

        // 5. BreaklotSecondCoating fallback (JSON safe)
        $breaklotSecond = BreaklotSecondCoating::where([
            'mass_prod' => $massProd,
            'furnace'   => $furnace,
            'layer'     => $layer,
            'model'     => $model,
            'lot_no'    => $lotNo,
        ])->first();

        $breaklotRemarks = $safeJsonRemarks($breaklotSecond->coating_info_2ndgbdp ?? null);

        if ($matchesLongAging($breaklotRemarks)) {
            return response()->json([
                'found_coating_remarks' => true,
                'remarks' => $breaklotRemarks,
            ]);
        }

        return response()->json($response);
    }
}
