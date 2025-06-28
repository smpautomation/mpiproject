<?php

namespace App\Http\Controllers;

use App\Models\GxModel;
use Illuminate\Http\Request;

class GxModelController extends Controller
{
    public function index()
    {
        return GxModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return GxModel::create($validated);
    }

    public function show($id)
    {
        return GxModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = GxModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = GxModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
