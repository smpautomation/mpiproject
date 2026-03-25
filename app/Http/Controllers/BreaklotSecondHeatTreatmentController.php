<?php

namespace App\Http\Controllers;

use App\Models\BreaklotSecondHeatTreatment;
use Illuminate\Http\Request;

class BreaklotSecondHeatTreatmentController extends Controller
{
    public function index()
    {
        return BreaklotSecondHeatTreatment::all()->map(function ($item) {
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
            'model'     => 'nullable|string|max:255',
            'lot_no'    => 'nullable|string|max:255',
            'gbdp_1st'  => 'nullable|array',
            'gbdp_2nd'  => 'nullable|array',
        ]);

        $validated['gbdp_1st'] = json_encode($validated['gbdp_1st'] ?? []);
        $validated['gbdp_2nd'] = json_encode($validated['gbdp_2nd'] ?? []);

        return BreaklotSecondHeatTreatment::create($validated);
    }

    public function show($id)
    {
        $item = BreaklotSecondHeatTreatment::findOrFail($id);
        $item->gbdp_1st = json_decode($item->gbdp_1st, true);
        $item->gbdp_2nd = json_decode($item->gbdp_2nd, true);

        return $item;
    }

    public function update(Request $request, $id)
    {
        $item = BreaklotSecondHeatTreatment::findOrFail($id);

        $validated = $request->validate([
            'furnace' => 'sometimes|string|max:255',
            'mass_prod' => 'sometimes|string|max:255',
            'layer'     => 'nullable|numeric',
            'model'     => 'sometimes|string|max:255',
            'lot_no'    => 'sometimes|string|max:255',
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
        $item = BreaklotSecondHeatTreatment::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
