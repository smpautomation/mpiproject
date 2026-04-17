<?php

namespace App\Http\Controllers;

use App\Models\HisModel;
use Illuminate\Http\Request;

class HisModelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return HisModel::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        return HisModel::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return HisModel::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $model = HisModel::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $model = HisModel::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
