<?php

namespace App\Http\Controllers;
use App\Models\MiasFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MiasFactorController extends Controller{

    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => MiasFactor::all()
        ]);
    }

    public function show($id)
    {
        $mias = MiasFactor::find($id);

        if (!$mias) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $mias
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'employee_no' => 'nullable|string|max:100',
            'mias_no' => 'nullable|string|max:100',
        ]);

        $mias = MiasFactor::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'MiasFactor record created',
            'data' => $mias
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'employee_no' => 'nullable|string|max:100',
            'mias_no' => 'nullable|string|max:100',
        ]);

        $record = MiasFactor::findOrFail($id);
        $record->update($validated);

        return response()->json($record);
    }


    public function bulkUpload(Request $request)
    {
        $data = $request->input('data');

        if (!is_array($data) || empty($data)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid or empty data provided',
            ], 400);
        }

        // Validate required columns exist in the first row
        $requiredColumns = ['Employee_Name', 'Employee_Number', 'User_Number'];
        $missingColumns = [];

        foreach ($requiredColumns as $column) {
            if (!array_key_exists($column, $data[0])) {
                $missingColumns[] = $column;
            }
        }

        if (!empty($missingColumns)) {
            return response()->json([
                'status' => false,
                'message' => 'Missing required columns: ' . implode(', ', $missingColumns),
            ], 422);
        }

        DB::beginTransaction();

        try {
            $insertData = [];

            foreach ($data as $row) {
                if (!array_filter($row)) continue;

                $insertData[] = [
                    'employee_name' => $row['Employee_Name'],
                    'employee_no' => $row['Employee_Number'],
                    'mias_no' => $row['User_Number'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (empty($insertData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid rows to insert (all rows may have been empty)',
                ], 400);
            }

            MiasFactor::insert($insertData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Bulk inspection data uploaded successfully',
                'inserted' => count($insertData),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Bulk upload failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            return response()->json([
                'status' => false,
                'message' => 'Bulk upload failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}

