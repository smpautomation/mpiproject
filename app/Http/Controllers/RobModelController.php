<?php

namespace App\Http\Controllers;

use App\Models\RobModel;
use Illuminate\Http\Request;

class RobModelController extends Controller
{
    public function index()
    {
        return RobModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return RobModel::create($validated);
    }

    public function show($id)
    {
        return RobModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = RobModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = RobModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
