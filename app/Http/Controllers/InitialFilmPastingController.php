<?php

namespace App\Http\Controllers;

use App\Models\InitialFilmPasting;
use Illuminate\Http\Request;

class InitialFilmPastingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            InitialFilmPasting::latest()->get(),
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name'        => 'nullable|string',
            'lot_no'            => 'nullable|string',
            'film_pasting_date' => 'nullable|string',
            'machine_no'        => 'nullable|string',
            'total_magnet_weight' => 'nullable|numeric',
            'loader_operator'   => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'checker_operator'  => 'nullable|string',
            'film_coating_amount' => 'nullable|string',
            'time_start'        => 'nullable',
            'time_finished'     => 'nullable',
            'remarks'           => 'nullable|string',
            'film_type'         => 'nullable|string',
            'film_class'        => 'nullable|string',
            'h_line_parameters' => 'nullable|array',
            't_line_parameters' => 'nullable|array',
            'setter_sand'       => 'nullable|boolean',
        ]);

        $filmPasting = InitialFilmPasting::create($validated);

        return response()->json($filmPasting, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InitialFilmPasting $initialFilmPasting)
    {
        return response()->json($initialFilmPasting, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InitialFilmPasting $initialFilmPasting)
    {
        $validated = $request->validate([
            'model_name'        => 'nullable|string',
            'lot_no'            => 'nullable|string',
            'film_pasting_date' => 'nullable|string',
            'machine_no'        => 'nullable|string',
            'total_magnet_weight' => 'nullable|numeric',
            'loader_operator'   => 'nullable|string',
            'unloader_operator' => 'nullable|string',
            'checker_operator'  => 'nullable|string',
            'film_coating_amount' => 'nullable|string',
            'time_start'        => 'nullable',
            'time_finished'     => 'nullable',
            'remarks'           => 'nullable|string',
            'film_type'         => 'nullable|string',
            'film_class'        => 'nullable|string',
            'h_line_parameters' => 'nullable|array',
            't_line_parameters' => 'nullable|array',
            'setter_sand'       => 'nullable|boolean',
        ]);

        $initialFilmPasting->update($validated);

        return response()->json($initialFilmPasting, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InitialFilmPasting $initialFilmPasting)
    {
        $initialFilmPasting->delete();

        return response()->json(['message' => 'Deleted'], 200);
    }
}
