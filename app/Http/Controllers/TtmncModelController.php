<?php

namespace App\Http\Controllers;

use App\Models\TtmncModel;
use Illuminate\Http\Request;

class TtmncModelController extends Controller
{
    public function index()
    {
        return TtmncModel::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return TtmncModel::create($validated);
    }

    public function show($id)
    {
        return TtmncModel::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = TtmncModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = TtmncModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
