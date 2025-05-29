<?php

namespace App\Http\Controllers;

use App\Models\InspectionData;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InspectionDataController extends Controller
{
    public function index(Request $request)
    {
        try{
            $inspectionData = InspectionData::all();
            return response()->json([
                'status' => true,
                'message' => 'Inspection Data fetched successfully',
                'data'=> $inspectionData
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message'=> "Inspection Data could not be fetched",
                "error"=> $e->getMessage()
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
                "time_loading" => $request->input("time_loading"),
                "temperature_1" => $request->input("temperature_1"),
                "date" => $request->input("date"),
                "time_unloading" => $request->input("time_unloading"),
                "temperature_2" => $request->input("temperature_2"),
                "shift" => $request->input("shift"),
                "operator" => $request->input("operator"),
                "mpi_sample" => $request->input("mpi_sample"),
                "ihc_ihk" => $request->input("ihc_ihk"),
                "br_4-pie-la" => $request->input("br_4-pie-la"),
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
                    'model' => $row['Model'] ?? null,
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
                    'oven_machine_no' => $row['Oven Machine No'] ?? null,
                    'time_loading' => $this->sanitizeTime($row['Time Loading'] ?? null),
                    'temperature_1' => $this->sanitizeTemperature($row['Temperature1'] ?? null),
                    'date' => (!empty($row['Date']) && strtolower(trim($row['Date'])) !== 'n/a') ? $row['Date'] : null,
                    'time_unloading' => $this->sanitizeTime($row['Time Unloading'] ?? null),
                    'temperature_2' => $this->sanitizeTemperature($row['Temperature2'] ?? null),
                    'shift' => $this->sanitizeField($row['Shift'] ?? null),
                    'operator' => $this->sanitizeField($row['Operator'] ?? null),
                    'mpi_sample' => $this->sanitizeField($row['MPI sample'] ?? null),
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

            \Log::error('Bulk upload error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Bulk upload failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
