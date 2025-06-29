<?php

namespace App\Http\Controllers;

use App\Models\VtModel;
use Illuminate\Http\Request;

class VtModelController extends Controller
{
    public function index()
    {
        return VtModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return VtModel::create($validated);
    }

    public function show($id)
    {
        return VtModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = VtModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = VtModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
