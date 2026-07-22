<?php

namespace App\Http\Controllers;

use App\Models\InspectionData;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InspectionDataController extends Controller
{
    public function index(Request $request)
    {
        try {
            $inspectionData = InspectionData::orderBy('model', 'asc')->get();

            return response()->json([
                'status'  => true,
                'message' => 'Inspection Data fetched successfully',
                'data'    => $inspectionData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Inspection Data could not be fetched',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try{
            $inspectionData = InspectionData::find($id);
            if(!empty($inspectionData)){
                return response()->json([
                    "status"=> true,
                    "message"=> "Inspection Data with ID: {$id} fetched successfully",
                    "data"=> $inspectionData
                ], 200);
            }else{
                return response()->json([
                    "status"=> false,
                    "message"=> "Inspection Data with ID: {$id} not found",
                ], 404);
            }

        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> "Inspection Data could not be fetched",
                "error"=> $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        DB::beginTransaction();

        try{
            $inspectionDataInputs = [
                "model" => $request->input("model"),
                "length" => $request->input("length"),
                "width" => $request->input("width"),
                "thickness" => $request->input("thickness"),
                "material_grade" => $request->input("material_grade"),
                "br" => $request->input("br"),
                "ihc" => $request->input("ihc"),
                "ihk" => $request->input("ihk"),
                "oven_machine_no" => $request->input("oven_machine_no"),
                "mpi_sample" => $request->input("mpi_sample"),
                "is_automotive" => $request->input("is_automotive"),
                "encoded_by" => $request->input("encoded_by"),
            ];
            $inspectionData = InspectionData::create($inspectionDataInputs);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Inspection Data saved successfully',
                'data' => $inspectionData
            ], 201);
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => "Inspection Data could not be saved",
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $inspectionData = InspectionData::findorfail( $id );

            $inputData = $request->all();
            $inspectionData->update($inputData);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Inspection Data updated successfully',
                'data' => $inspectionData
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error updating Inspection Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try{
            $inspectionData = InspectionData::findorfail($id);
            $inspectionData->delete();
            return response()->json([
                'status' => true,
                'message' => 'tmp Data deleted successfully'
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Error deleting tmp Data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function sanitizeTemperature($temp)
    {
        if (!isset($temp)) return null;

        $temp = trim($temp);

        // Handle junk values
        if (strtolower($temp) === 'n/a' || $temp === '' || $temp === '-') {
            return null;
        }

        // Remove common units/symbols
        $temp = preg_replace('/[^\d.\-]/', '', $temp); // keep only digits, dot, minus

        // If it still isn't a number, kill it
        if (!is_numeric($temp)) return null;

        return (float) $temp;
    }

    private function sanitizeModel($value)
    {
        if (!isset($value)) return null;

        $value = trim($value);

        // Reject obvious garbage
        if (strtolower($value) === 'n/a' || $value === '') return null;

        // Remove all dashes
        $value = str_replace('-', '', $value);

        return $value;
    }

    private function sanitizeField($value)
    {
        if (!isset($value)) return null;
        $value = trim($value);
        return (strtolower($value) === 'n/a' || $value === '') ? null : $value;
    }

    private function sanitizeTime($value)
    {
        if (!isset($value)) return null;
        $value = trim($value);

        // Reject common non-time inputs
        if (in_array(strtolower($value), ['n/a', 'na', '-', '--', ''], true)) {
            return null;
        }

        // Validate actual time format (e.g., HH:MM or HH:MM:SS)
        if (preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $value)) {
            return $value;
        }

        return null;
    }

    private function keepNAorEmpty($value): ?string
    {
        if (!isset($value)) return null;

        $value = trim((string) $value);

        if (strtolower($value) === 'n/a') return 'N/A';
        if ($value === '') return '';

        return $value;
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

        DB::beginTransaction();

        try {
            $insertData = [];

            foreach ($data as $row) {
                if (!array_filter($row)) continue;

                $thickness = $row['T'] ?? null;
                if ($thickness !== null) {
                    $thickness = trim($thickness);
                    $thickness = preg_replace('/\s*mm$/i', '', $thickness);
                    $thickness = $thickness === '' ? null : (float)$thickness;
                } else {
                    $thickness = null;
                }

                $insertData[] = [
                    'model' => $this->sanitizeModel($row['Model'] ?? null),
                    'length' => $row['L'] ?? null,
                    'width' => $row['W'] ?? null,
                    'thickness' => $thickness,
                    'material_grade' => $row['Material Grade'] ?? null,
                    'br' => trim(
                        ($row['Br1'] ?? '') . ' ' .
                        ($row['Br2'] ?? '') . ' ' .
                        ($row['Br3'] ?? '')
                    ),
                    'ihc' => $row['iHc'] ?? null,
                    'ihk' => $row['iHk'] ?? null,
                    'oven_machine_no' => $row['Oven'] ?? null,
                    'mpi_sample' => $this->keepNAorEmpty($row['MPI sample'] ?? null),
                    'is_automotive' => $row['Automotive'] ?? null,
                    'encoded_by' => $row['Encoded By'] ?? null,
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

            InspectionData::insert($insertData);

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

    public function checkDuplicate(Request $request)
    {
        $request->validate([
            'model' => ['required', 'string'],
        ]);

        $exists = InspectionData::where('model', $request->model)->exists();

        return response()->json([
            'duplicate' => $exists,
        ]);
    }

    public function fetchAllModelNames()
    {
        $modelNames = InspectionData::whereNotNull('model')
            ->distinct()
            ->orderBy('model', 'asc')
            ->pluck('model');

        return response()->json($modelNames);
    }


    public function saveNewRule(Request $request)
    {
        // 1. Validate incoming patch data payload
        $validatedData = $request->validate([
            'model'           => 'required|string|max:255',
            'pattern_list'    => 'required|array|min:1',
            'layer_list'      => 'required|array|min:1',
            'rule_encoded_by' => 'required|string|max:255',
        ]);

        try {
            // 2. Find the existing inspection target by its model name
            $rule = InspectionData::where('model', $validatedData['model'])->first();

            // 3. Fallback error if that model configuration row doesn't exist yet
            if (!$rule) {
                return response()->json([
                    'success' => false,
                    'message' => "Configuration rule for model '{$validatedData['model']}' was not found."
                ], 404);
            }

            // 4. Perform the partial document update
            $rule->update([
                'pattern_list'    => $validatedData['pattern_list'],
                'layer_list'      => $validatedData['layer_list'],
                'rule_encoded_by' => $validatedData['rule_encoded_by'],
            ]);

            // 5. Respond back with HTTP 200 (OK) instead of 201 (Created)
            return response()->json([
                'success' => true,
                'message' => 'Model Rule updated successfully.',
                'data'    => $rule
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database update error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function ruleList(Request $request)
    {
        // 1. SELECT your 4 fields + the ordering column to prevent strict SQL errors
        $query = InspectionData::select([
            'model',
            'pattern_list',
            'layer_list',
            'rule_encoded_by',
            'updated_at' // <--- Added to make latest('updated_at') database-safe
        ]);

        // 2. Filter out null values
        $query->whereNotNull('model')
            ->whereNotNull('pattern_list')
            ->whereNotNull('layer_list')
            ->whereNotNull('rule_encoded_by');

        // 3. Search Filter Component
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            
            $query->where(function ($subQuery) use ($searchTerm) {
                $subQuery->where('model', 'like', "%{$searchTerm}%")
                        ->orWhere('rule_encoded_by', 'like', "%{$searchTerm}%");
            });
        }

        try {
            // 4. Safely paginate using the selected 'updated_at' column
            $paginatedRules = $query->latest('updated_at')->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $paginatedRules->items(),
                'pagination' => [
                    'current_page' => $paginatedRules->currentPage(),
                    'per_page'     => $paginatedRules->perPage(),
                    'total'        => $paginatedRules->total(),
                    'last_page'    => $paginatedRules->lastPage(),
                    'from'         => $paginatedRules->firstItem() ?? 0,
                    'to'           => $paginatedRules->lastItem() ?? 0
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve model rule logs: ' . $e->getMessage(),
                'data' => [],
                'pagination' => null
            ], 500);
        }
    }

    public function checkExisting(Request $request)
    {
        $validatedData = $request->validate([
            'model' => 'required|string|max:255',
        ]);

        // 1. Locate the first record matching the target model name
        $rule = InspectionData::where('model', $validatedData['model'])->first();
        
        if (!$rule) {
            return response()->json([
                'success' => false,
                'rule_existing' => false,
                'message' => "Configuration rule for model '{$validatedData['model']}' was not found."
            ], 404);
        }

        // 2. Perform validation to ensure all three tracking parameters exist and are not empty
        // Using empty() handles both null fields and empty JSON arrays ([]) safely.
        $isFullyConfigured = !empty($rule->pattern_list) && 
                            !empty($rule->layer_list) && 
                            !empty($rule->rule_encoded_by);

        if ($isFullyConfigured) {
            return response()->json([
                'success'       => true,
                'rule_existing' => true,
                'message'       => "A fully configured rule already exists for model '{$rule->model}'.",
                'data'          => [
                    'model'           => $rule->model,
                    'pattern_list'    => $rule->pattern_list,
                    'layer_list'      => $rule->layer_list,
                    'rule_encoded_by' => $rule->rule_encoded_by
                ]
            ], 200);
        }

        // 3. Fallback: The row exists, but configurations are missing or empty
        return response()->json([
            'success'       => true,
            'rule_existing' => false,
            'message'       => "Model row exists, but configuration rules are incomplete or empty."
        ], 200);
    }
}
