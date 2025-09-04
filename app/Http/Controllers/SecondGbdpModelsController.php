<?php

namespace App\Http\Controllers;

use App\Models\Second_Gbdp_Models;
use Illuminate\Http\Request;

class SecondGBDPModelsController extends Controller
{
    public function index()
    {
        return Second_Gbdp_Models::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
        ]);

        return Second_Gbdp_Models::create($validated);
    }

    public function show($id)
    {
        return Second_Gbdp_Models::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $secondGBDP = Second_Gbdp_Models::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
        ]);

        $secondGBDP->update($validated);

        return $secondGBDP;
    }

    public function destroy($id)
    {
        $secondGBDP = Second_Gbdp_Models::findOrFail($id);
        $secondGBDP->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
