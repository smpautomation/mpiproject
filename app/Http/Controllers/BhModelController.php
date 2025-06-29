<?php

namespace App\Http\Controllers;

use App\Models\BhModel;
use Illuminate\Http\Request;

class BhModelController extends Controller
{
    public function index()
    {
        return BhModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return BhModel::create($validated);
    }

    public function show($id)
    {
        return BhModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = BhModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = BhModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
