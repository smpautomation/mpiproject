<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DevButtonControls;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DevButtonControlsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $controls = DevButtonControls::all();
        return response()->json($controls);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'is_strict_val_ip_cs' => 'required|boolean',
        ]);

        $control = DevButtonControls::create($request->only('is_strict_val_ip_cs'));

        return response()->json($control, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(DevButtonControls $devButtonControl)
    {
        return response()->json($devButtonControl);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DevButtonControls $devButtonControl)
    {
        $request->validate([
            'is_strict_val_ip_cs' => 'required|boolean',
        ]);

        $devButtonControl->update($request->only('is_strict_val_ip_cs'));

        return response()->json($devButtonControl);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DevButtonControls $devButtonControl)
    {
        $devButtonControl->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
