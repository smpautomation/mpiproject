<?php

namespace App\Http\Controllers;

use App\Models\FilmPastingData;
use Illuminate\Http\Request;

class FilmPastingDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FilmPastingData::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|numeric',
            'date' => 'nullable|date',
            'machine_no' => 'nullable|string',
            'total_magnet_weight' => 'nullable|numeric',
            'loader_operator' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'checker_operator' => 'nullable|string',
            'film_coating_amount' => 'nullable|string',
            'time_start' => 'nullable|date_format:H:i',
            'time_finished' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string',
            'film_type' => 'nullable|string',
            'film_class' => 'nullable|string',
            'h_line_parameters' => 'nullable|array',
            't_line_parameters' => 'nullable|array',
            'setter_sand' => 'boolean',
        ]);

        $filmPastingData = FilmPastingData::create($validated);

        return response()->json($filmPastingData, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(FilmPastingData $filmPastingData)
    {
        return $filmPastingData;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FilmPastingData $filmPastingData)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string',
            'layer' => 'nullable|numeric',
            'date' => 'nullable|date',
            'machine_no' => 'nullable|string',
            'total_magnet_weight' => 'nullable|numeric',
            'loader_operator' => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'checker_operator' => 'nullable|string',
            'film_coating_amount' => 'nullable|string',
            'time_start' => 'nullable|date_format:H:i',
            'time_finished' => 'nullable|date_format:H:i',
            'remarks' => 'nullable|string',
            'film_type' => 'nullable|string',
            'film_class' => 'nullable|string',
            'h_line_parameters' => 'nullable|array',
            't_line_parameters' => 'nullable|array',
            'setter_sand' => 'boolean',
        ]);

        $filmPastingData->update($validated);

        return response()->json($filmPastingData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FilmPastingData $filmPastingData)
    {
        $filmPastingData->delete();

        return response()->json(null, 204);
    }

    public function getLayersByMassProd($massProd)
    {
        // Fetch only the 'layer' column where mass_prod matches
        $layers = FilmPastingData::where('mass_prod', $massProd)
            ->pluck('layer'); // pluck returns only the values of the given column

        return response()->json([
            'mass_prod' => $massProd,
            'layers' => $layers,
        ]);
    }
}
