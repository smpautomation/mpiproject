<?php

namespace App\Http\Controllers;

use App\Models\GbdpSecondFilmPaste;
use Illuminate\Http\Request;

class GbdpSecondFilmPasteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GbdpSecondFilmPaste::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'nullable|string',
            'furnace' => 'nullable|string',
            'layer' => 'nullable|string',
            'model' => 'nullable|string',
            'lot_no' => 'nullable|string',

            'second_mass_prod' => 'nullable|string',
            'second_furnace' => 'nullable|string',
            'second_layer' => 'nullable|string',

            'film_type' => 'nullable|string',
            'film_class' => 'nullable|string',

            'encoded_by' => 'nullable|string',
        ]);

        $record = GbdpSecondFilmPaste::create($validated);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $record
        ], 201);
    }

    public function checkExisting(Request $request)
    {
        $request->validate([
            'model'            => 'nullable|string',
            'lot_no'           => 'nullable|string',
            'second_mass_prod' => 'nullable|string',
            'second_furnace'   => 'nullable|string',
            'second_layer'     => 'nullable|string',
        ]);

        $secondFilmPaste = GbdpSecondFilmPaste::where('second_furnace', $request->second_furnace)
            ->where('second_mass_prod', $request->second_mass_prod)
            ->where('second_layer', $request->second_layer)
            ->where('model', $request->model)
            ->where('lot_no', $request->lot_no)
            ->exists();

        return response()->json([
            'message' => 'Created successfully',
            'second_film_exists' => $secondFilmPaste
        ]);
    }
}
