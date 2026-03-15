<?php

namespace App\Http\Controllers;

use App\Models\CpkBrModels;
use Illuminate\Http\Request;

class CpkBrModelsController extends Controller
{
    public function index()
    {
        return CpkBrModels::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return CpkBrModels::create($validated);
    }

    public function show($id)
    {
        return CpkBrModels::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = CpkBrModels::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = CpkBrModels::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
