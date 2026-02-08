<?php

namespace App\Http\Controllers;

use App\Models\BreaklotCoating;
use App\Models\BreaklotInitialLot;
use App\Models\BreaklotSecondCoating;
use App\Models\BreaklotFilmpasting;
use App\Models\TPMData;
use App\Models\TPMDataCategory;
use Illuminate\Http\Request;

class BreaklotCoatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreaklotCoating::orderBy('date', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'nullable|date',
            'furnace' => 'nullable|string',
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|string',
            'layer_code' => 'nullable|string',
            'lot_no' => 'nullable|string',
            'model' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'slurry_lot_no' => 'nullable|string',
            'loader_operator' => 'nullable|string',
            'min_tb_content' => 'nullable|string',
            'sample_qty' => 'nullable|string',
            'total_magnet_weight' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'checker_operator' => 'nullable|string',
            'coating_data' => 'nullable|array',
            'time_start' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finished' => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'maximum' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'average' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);

        $record = BreaklotCoating::create([
            ...$validated,
            'coating_data' => isset($validated['coating_data'])
                ? json_encode($validated['coating_data'])
                : null,
        ]);

        return response()->json($record, 201);
    }

    public function show(BreaklotCoating $breaklotCoating)
    {
        return response()->json($breaklotCoating);
    }

    public function update(Request $request, $id)
    {
        $breaklot = BreaklotCoating::findOrFail($id);

        $validated = $request->validate([
            'date' => 'sometimes|nullable|date',
            'furnace' => 'sometimes|nullable|string',
            'mass_prod' => 'sometimes|nullable|string',
            'layer' => 'sometimes|nullable|string',
            'layer_code' => 'sometimes|nullable|string',
            'lot_no' => 'sometimes|nullable|string',
            'model' => 'sometimes|nullable|string',
            'machine_no' => 'sometimes|nullable|string',
            'slurry_lot_no' => 'sometimes|nullable|string',
            'loader_operator' => 'sometimes|nullable|string',
            'min_tb_content' => 'sometimes|nullable|string',
            'sample_qty' => 'sometimes|nullable|string',
            'total_magnet_weight' => 'sometimes|nullable|string',
            'unloader_operator' => 'sometimes|nullable|string',
            'checker_operator' => 'sometimes|nullable|string',
            'coating_data' => 'sometimes|nullable|array',
            'time_start' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finished' => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'maximum' => 'sometimes|nullable|numeric',
            'minimum' => 'sometimes|nullable|numeric',
            'average' => 'sometimes|nullable|numeric',
            'remarks' => 'sometimes|nullable|string',
        ]);

        if (isset($validated['coating_data'])) {
            $validated['coating_data'] = json_encode($validated['coating_data']);
        }

        $breaklot->update($validated);

        //Log::info('Manually patched breaklot coating', ['id' => $breaklot->id]);

        return response()->json($breaklot);
    }

    public function destroy(BreaklotCoating $breaklotCoating)
    {
        $breaklotCoating->delete();
        return response()->json(null, 204);
    }

    public function generateLayerCode(Request $request)
    {
        $validated = $request->validate([
            'furnace'   => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
        ]);

        // Strip hyphens from furnace for final code
        $furnaceCode = str_replace('-', '', $validated['furnace']);

        // Collect latest records from all related tables
        $records = collect([
            BreaklotCoating::where($validated)->orderByDesc('layer_code')->first(),
            BreaklotSecondCoating::where($validated)->orderByDesc('layer_code')->first(),
            BreaklotFilmpasting::where($validated)->orderByDesc('layer_code')->first(),
        ])->filter(); // remove nulls

        // Default starting letter
        $nextLetter = 'B';

        if ($records->isNotEmpty()) {
            // Get the highest last-letter among all tables
            $maxLetter = $records
                ->map(fn ($r) => substr($r->layer_code, -1))
                ->max();

            $nextLetter = chr(ord($maxLetter) + 1);
        }

        $layerCode = "{$furnaceCode}-{$validated['mass_prod']}-{$validated['layer']}-{$nextLetter}";

        return response()->json([
            'layer_code' => $layerCode,
        ]);
    }


    public function checkExisting(Request $request)
    {
        $validated = $request->validate([
            'furnace'   => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
            'lot_no'    => 'required|string',
        ]);

        $furnace   = $validated['furnace'];
        $massProd  = $validated['mass_prod'];
        $layer     = $validated['layer'];
        $lotNo     = $validated['lot_no'];

        // Get all lot numbers from BreaklotCoating
        $coatingLots = BreaklotCoating::where([
            'furnace'   => $furnace,
            'mass_prod' => $massProd,
            'layer'     => $layer,
        ])->pluck('lot_no')->toArray();

        $secondCoatingLots = BreaklotSecondCoating::where([
            'furnace'   => $furnace,
            'mass_prod' => $massProd,
            'layer'     => $layer,
        ])->pluck('lot_no')->toArray();

        $filmPastingLots = BreaklotFilmpasting::where([
            'furnace'   => $furnace,
            'mass_prod' => $massProd,
            'layer'     => $layer,
        ])->pluck('lot_no')->toArray();

        // Get all initial lots from BreaklotInitialLot
        $initialLots = BreaklotInitialLot::where([
            'furnace'   => $furnace,
            'mass_prod' => $massProd,
            'layer'     => $layer,
        ])->pluck('initial_lot')->toArray();

        // Merge both arrays
        $allLots = array_merge($coatingLots, $initialLots, $secondCoatingLots, $filmPastingLots);

        // Check if current lot exists
        $exists = in_array($lotNo, $allLots);

        return response()->json([
            'exists' => $exists,
        ]);
    }

    public function checkExistingForTpm(Request $request)
    {
        $validated = $request->validate([
            'furnace'   => 'required|string',
            'mass_prod' => 'required|string',
            'layer'     => 'required|string',
            'lot_no'    => 'required|string',
        ]);

        // 1. Get all serial numbers linked to the lot
        $serialNumbers = TPMDataCategory::where('jhcurve_lotno', $validated['lot_no'])
            ->pluck('tpm_data_serial');

        if ($serialNumbers->isEmpty()) {
            return response()->json([
                'exists' => false,
                'reason' => 'No serials found for lot',
            ]);
        }

        // 2. Check if any serial matches TPMData constraints
        $exists = TPMData::whereIn('serial_no', $serialNumbers)
            ->where('mass_prod', $validated['mass_prod'])
            ->where('furnace', $validated['furnace'])
            ->where('layer_no', $validated['layer'])
            ->exists();

        return response()->json([
            'exists' => $exists,
        ]);
    }


}
