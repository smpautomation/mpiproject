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
            'hourly_checking' => 'nullable|array',
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
            'hourly_checking' => 'nullable|array',
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

    public function checkDuplicateLot(Request $request)
    {
        $request->validate([
            'lot_no'     => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ]);

        // Check based only on lot_no
        $lotExists = InitialFilmPasting::where('lot_no', $request->lot_no)->exists();

        // Check based on the pair (model_name + lot_no)
        $pairExists = InitialFilmPasting::where('lot_no', $request->lot_no)
            ->where('model_name', $request->model_name)
            ->exists();

        return response()->json([
            'duplicate_lot'  => $lotExists,
            'duplicate_pair' => $pairExists,
        ]);
    }

    public function fetchFilmPasteSummaryData(Request $request)
    {
        $validated = $request->validate([
            'lot_no'     => 'required|string|max:50',
            'model_name' => 'required|string|max:100',
        ]);

        $record = InitialFilmPasting::where('lot_no', $validated['lot_no'])
            ->where('model_name', $validated['model_name'])
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'No record found.'
            ], 404);
        }

        return response()->json($record);
    }
}
