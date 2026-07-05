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

            'second_mass_prod' => 'nullable|string',
            'second_furnace' => 'nullable|string',
            'second_layer' => 'nullable|string',
        ]);

        $record = GbdpSecondFilmPaste::create($validated);

        return response()->json([
            'message' => 'Created successfully',
            'data' => $record
        ], 201);
    }
}
