<?php

namespace App\Http\Controllers;

use App\Models\BreaklotFilmpasting;
use Illuminate\Http\Request;

class BreaklotFilmpastingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BreaklotFilmpasting::orderBy('date', 'desc')->get();
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
            'date'                   => 'nullable|date',
            'machine_no'             => 'nullable|string',
            'total_magnet_weight'    => 'nullable|numeric',
            'loader_operator'        => 'nullable|string',
            'unloader_operator'      => 'nullable|string',
            'checker_operator'       => 'nullable|string',
            'film_coating_amount'    => 'nullable|string',
            'time_start'             => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finished'          => 'nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'remarks'                => 'nullable|string',
            'film_type'              => 'nullable|string',
            'film_class'             => 'nullable|string',
            'setter_sand'            => 'nullable|boolean',

            'h_line_parameters'      => 'nullable|array',
            't_line_parameters'      => 'nullable|array',
        ]);

        $record = BreaklotFilmpasting::create([
            ...$validated,

            'h_line_parameters' => isset($validated['h_line_parameters'])
                ? json_encode($validated['h_line_parameters'])
                : null,

            't_line_parameters' => isset($validated['t_line_parameters'])
                ? json_encode($validated['t_line_parameters'])
                : null,
        ]);

        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BreaklotFilmpasting $breaklotFilmpasting)
    {
        return response()->json($breaklotFilmpasting);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $record = BreaklotFilmpasting::findOrFail($id);

        $validated = $request->validate([
            'model'               => 'sometimes|nullable|string',
            'machine_no'          => 'sometimes|nullable|string',
            'total_magnet_weight' => 'sometimes|nullable|numeric',
            'loader_operator'     => 'sometimes|nullable|string',
            'unloader_operator'   => 'sometimes|nullable|string',
            'checker_operator'    => 'sometimes|nullable|string',
            'film_coating_amount' => 'sometimes|nullable|string',
            'time_start'          => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'time_finished'       => 'sometimes|nullable|regex:/^\d{2}:\d{2}(:\d{2})?$/',
            'remarks'             => 'sometimes|nullable|string',
            'film_type'           => 'sometimes|nullable|string',
            'film_class'          => 'sometimes|nullable|string',
            'setter_sand'         => 'sometimes|nullable|boolean',

            'h_line_parameters'   => 'sometimes|nullable|array',
            't_line_parameters'   => 'sometimes|nullable|array',
        ]);

        if (isset($validated['h_line_parameters'])) {
            $validated['h_line_parameters'] = json_encode($validated['h_line_parameters']);
        }

        if (isset($validated['t_line_parameters'])) {
            $validated['t_line_parameters'] = json_encode($validated['t_line_parameters']);
        }

        $record->update($validated);

        return response()->json($record);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BreaklotFilmpasting $breaklotFilmpasting)
    {
        $breaklotFilmpasting->delete();
        return response()->json(null, 204);
    }
}
