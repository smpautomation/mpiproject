<?php

namespace App\Http\Controllers;

use App\Models\FurnaceData;
use Illuminate\Http\Request;

class FurnaceDataController extends Controller
{
    public function index()
    {
        return FurnaceData::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'furnace_name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:255',
            'encoded_by' => 'nullable|string|max:255'
        ]);

        return FurnaceData::create($validated);
    }

    public function show($id)
    {
        return FurnaceData::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $furnace = FurnaceData::findOrFail($id);

        $validated = $request->validate([
            'furnace_name' => 'required|string|max:255',
            'description'  => 'nullable|string|max:255',
            'encoded_by' => 'nullable|string|max:255',
        ]);

        $furnace->update($validated);
        return $furnace;
    }

    public function destroy($id)
    {
        $furnace = FurnaceData::findOrFail($id);
        $furnace->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
