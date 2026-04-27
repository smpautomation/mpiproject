<?php

namespace App\Http\Controllers;

use App\Models\LayerDeleteRequest;
use Illuminate\Http\Request;

class LayerDeleteRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return LayerDeleteRequest::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod'         => 'required|string',
            'furnace'           => 'required|string',
            'layer'             => 'required|string',
            'reason'            => 'required|string',
            'corrective_action' => 'required|string',
            'request_by'        => 'required|string',
            'status'            => 'required|string'
        ]);

        $record = LayerDeleteRequest::create($validated);

        return response()->json($record, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LayerDeleteRequest $layerDeleteRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LayerDeleteRequest $layerDeleteRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LayerDeleteRequest $layerDeleteRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LayerDeleteRequest $layerDeleteRequest)
    {
        //
    }
}
