<?php

namespace App\Http\Controllers;

use App\Models\FieldEditRequest;
use Illuminate\Http\Request;

class FieldEditRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return FieldEditRequest::all();
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod'         => 'required|string',
            'furnace'           => 'required|string',
            'current_data'      => 'required|string',
            'new_data'          => 'required|string',
            'target_column'     => 'required|string',
            'reason'            => 'required|string',
            'corrective_action' => 'required|string',
            'request_by'        => 'required|string',
            'status'            => 'required|string'
        ]);

        $record = FieldEditRequest::create($validated);

        return response()->json($record, 201);
    }

    public function show(FieldEditRequest $fieldEditRequest)
    {
        //
    }


    public function edit(FieldEditRequest $fieldEditRequest)
    {
        //
    }


    public function update(Request $request, FieldEditRequest $fieldEditRequest)
    {
        //
    }


    public function destroy(FieldEditRequest $fieldEditRequest)
    {
        //
    }
}
