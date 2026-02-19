<?php

namespace App\Http\Controllers;

use App\Models\StampUndoHistory;
use Illuminate\Http\Request;

class StampUndoHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return StampUndoHistory::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod'    => 'string|nullable',
            'furnace'      => 'string|nullable',
            'serial_no'    => 'string|nullable',
            'model_name'   => 'string|nullable',
            'lot_no'       => 'string|nullable',
            'stamp_undone' => 'string|nullable',
            'undo_remarks' => 'string|nullable',
            'undo_by'      => 'string|nullable',
        ]);

        $stampHis = StampUndoHistory::create($validated);

        return response()->json($stampHis, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return StampUndoHistory::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $stampHis = StampUndoHistory::findOrFail($id);

        $validated = $request->validate([
            'mass_prod'    => 'string|nullable',
            'furnace'      => 'string|nullable',
            'serial_no'    => 'string|nullable',
            'model_name'   => 'string|nullable',
            'lot_no'       => 'string|nullable',
            'stamp_undone' => 'string|nullable',
            'undo_remarks' => 'string|nullable',
            'undo_by'      => 'string|nullable',
        ]);

        $stampHis->update($validated);
        return $stampHis;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stampHis = StampUndoHistory::findOrFail($id);
        $stampHis->delete();

        return response()->noContent();
    }

    public function bySerial($serial_no)
    {
        return StampUndoHistory::where('serial_no', $serial_no)
            ->orderByDesc('created_at')
            ->get();
    }
}
