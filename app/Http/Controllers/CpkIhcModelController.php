<?php

namespace App\Http\Controllers;

use App\Models\CpkIhcModel;
use Illuminate\Http\Request;

class CpkIhcModelController extends Controller
{
    public function index()
    {
        return CpkIhcModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return CpkIhcModel::create($validated);
    }

    public function show($id)
    {
        return CpkIhcModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = CpkIhcModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = CpkIhcModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
