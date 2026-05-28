<?php

namespace App\Http\Controllers;

use App\Models\BreaklotCoating;
use App\Models\BreaklotSecondCoating;
use App\Models\Coating;
use App\Models\CoatingPending;
use App\Models\GbdpSecondCoating;
use App\Models\BreaklotInitialLotHt;
use App\Models\MassProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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

    public function longAgingDetection(Request $request)
    {
        $request->validate([
            'furnace'   => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
            'model'     => 'required|string',
            'lot_no'    => 'required|string',
        ]);

        Log::info('Long aging check started', $request->all());

        $massProd = $request->mass_prod;
        $furnace  = $request->furnace;
        $layer    = $request->layer;
        $model    = $request->model;
        $lotNo    = $request->lot_no;

        $response = [
            'long_aging' => false,
            'days_diff'  => null,
        ];

        $massProduction = MassProduction::where([
            'mass_prod' => $massProd,
            'furnace'   => $furnace,
        ])->first();

        if (!$massProduction) {
            Log::warning('MassProduction not found', compact('massProd', 'furnace'));
            return response()->json($response);
        }

        if (!$massProduction->date_start) {
            Log::warning('MassProduction missing date_start', [
                'mass_prod' => $massProd,
                'furnace' => $furnace,
            ]);
            return response()->json($response);
        }

        $dateStartTs = strtotime($massProduction->date_start);

        Log::info('Date start resolved', [
            'date_start' => $massProduction->date_start,
            'timestamp' => $dateStartTs,
        ]);

        $coatingDate = null;

        $initialLot = BreaklotInitialLotHt::where([
            'mass_prod'     => $massProd,
            'furnace'       => $furnace,
            'layer'         => $layer,
            'initial_model' => $model,
            'initial_lot'   => $lotNo,
        ])->first();

        Log::info('Initial lot check', [
            'exists' => (bool) $initialLot,
        ]);

        if ($initialLot) {

            $coating = Coating::where([
                'mass_prod' => $massProd,
                'furnace'   => $furnace,
                'layer'     => $layer,
            ])->first();

            $coatingDate = $coating?->date;

            Log::info('Initial coating date (Coating table)', [
                'date' => $coatingDate,
            ]);

            if (!$coatingDate) {
                $gbdp = GbdpSecondCoating::where([
                    'mass_prod' => $massProd,
                    'furnace'   => $furnace,
                    'layer'     => $layer,
                ])->first();

                $coatingDate = $gbdp?->coating_info_2ndgbdp['date'] ?? null;

                Log::info('Fallback GBDP date', [
                    'date' => $coatingDate,
                ]);
            }
        } else {

            $breaklot = BreaklotCoating::where([
                'mass_prod' => $massProd,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $model,
                'lot_no'    => $lotNo,
            ])->first();

            $coatingDate = $breaklot?->date;

            Log::info('Breaklot coating date', [
                'date' => $coatingDate,
            ]);

            if (!$coatingDate) {
                $breaklotSecond = BreaklotSecondCoating::where([
                    'mass_prod' => $massProd,
                    'furnace'   => $furnace,
                    'layer'     => $layer,
                    'model'     => $model,
                    'lot_no'    => $lotNo,
                ])->first();

                $coatingDate = $breaklotSecond?->coating_info_2ndgbdp['date'] ?? null;

                Log::info('Breaklot second coating fallback', [
                    'date' => $coatingDate,
                ]);
            }
        }

        if (!$coatingDate) {
            Log::warning('No coating date resolved');
            return response()->json($response);
        }

        $coatingTs = strtotime($coatingDate);

        if (!$coatingTs) {
            Log::error('Invalid coating date format', [
                'coatingDate' => $coatingDate,
            ]);
            return response()->json($response);
        }

        $secondsDiff = $dateStartTs - $coatingTs;
        $daysDiff = floor($secondsDiff / 86400);

        Log::info('Date diff computed', [
            'coating' => $coatingDate,
            'date_start' => $massProduction->date_start,
            'days_diff' => $daysDiff,
        ]);

        if ($daysDiff > 7) {
            Log::info('LONG AGING DETECTED');

            return response()->json([
                'long_aging' => true,
                'days_diff'  => $daysDiff,
            ]);
        }

        return response()->json($response);
    }
}
