<?php

namespace App\Http\Controllers;

use App\Models\DataEditorLogs;
use Illuminate\Http\Request;

class DataEditorLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DataEditorLogs::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string|max:255',
            'furnace' => 'required|string|max:255',
            'layer' => 'required|string|max:255',
            'user_reason' => 'required|string',
            'user_corrective_action' => 'required|string',
            'user_verified_by' => 'required|string|max:255',
            'user_confirmation' => 'required|boolean',
            'log_remarks' => 'required|string',
        ]);

        return DataEditorLogs::create($validated);
    }

    public function show($id)
    {
        return DataEditorLogs::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $model = DataEditorLogs::findOrFail($id);

        $validated = $request->validate([
            'mass_prod' => 'required|string|max:255',
            'furnace' => 'required|string|max:255',
            'layer' => 'required|string|max:255',
            'user_reason' => 'required|string',
            'user_corrective_action' => 'required|string',
            'user_verified_by' => 'required|string|max:255',
            'user_confirmation' => 'required|boolean',
            'log_remarks' => 'required|string',
        ]);

        $model->update($validated);
        return $model;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $model = DataEditorLogs::findOrFail($id);
        $model->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }
}
