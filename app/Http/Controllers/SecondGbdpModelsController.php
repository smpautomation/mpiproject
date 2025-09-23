<?php

namespace App\Http\Controllers;

use App\Models\SecondGbdpModels;
use Illuminate\Http\Request;

class SecondGbdpModelsController extends Controller
{
    public function index()
    {
        return SecondGbdpModels::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
        ]);

        return SecondGbdpModels::create($validated);
    }

    public function show($id)
    {
        return SecondGbdpModels::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $secondGBDP = SecondGbdpModels::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'nullable|string|max:255',
        ]);

        $secondGBDP->update($validated);

        return $secondGBDP;
    }

    public function destroy($id)
    {
        $secondGBDP = SecondGbdpModels::findOrFail($id);
        $secondGBDP->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
