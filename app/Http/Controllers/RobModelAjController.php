<?php

namespace App\Http\Controllers;

use App\Models\RobModelAj;
use Illuminate\Http\Request;

class RobModelAjController extends Controller
{
    public function index()
    {
        return RobModelAj::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_name'         => 'required|string|max:255|unique:rob_model_ajs,model_name',
            'encoded_by'         => 'required|string|max:255',
            'br_rt_standardmax' => 'required|numeric',
            'br_rt_standardmin' => 'required|numeric',
            'br_vt_standard'    => 'required|numeric',
            'hd5_standard'      => 'required|numeric',
            'jd5_standard'      => 'required|numeric',
        ]);

        $model = RobModelAj::create($validated);

        return response()->json([
            'message' => 'ROB AJ Model created successfully',
            'data'    => $model
        ], 201);
    }

    public function show($id)
    {
        return RobModelAj::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $model = RobModelAj::findOrFail($id);

        $validated = $request->validate([
            'model_name' => 'required|string|max:255',
            'encoded_by' => 'required|string|max:255',
        ]);

        $model->update($validated);
        return $model;
    }

    public function destroy($id)
    {
        $model = RobModelAj::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
