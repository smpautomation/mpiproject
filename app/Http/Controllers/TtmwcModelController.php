<?php

namespace App\Http\Controllers;

use App\Models\TtmwcModel;
use Illuminate\Http\Request;

class TtmwcModelController extends Controller
{
    public function index()
    {
        return TtmwcModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return TtmwcModel::create($validated);
    }

    public function show($id)
    {
        return TtmwcModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = TtmwcModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = TtmwcModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
