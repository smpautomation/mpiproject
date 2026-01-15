<?php

namespace App\Http\Controllers;

use App\Models\MassProduction;
use App\Models\GbdpSecondCoating;
use App\Models\GbdpSecondHeatTreatment;
use App\Models\FilmPastingData;
use App\Models\Coating;
use App\Models\TPMData;
use App\Models\HtMassProData;
use App\Models\TPMDataAggregateFunctions;
use App\Models\ReportData;
use App\Models\SmpData;
use App\Models\ExcessLayers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class MassProductionController extends Controller
{
    public function index()
    {
        return MassProduction::whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('mass_productions')
                    ->whereNotNull('mass_prod')
                    ->groupBy('mass_prod');
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estimated_completion' => 'nullable|date',
            'mass_prod' => [
                'nullable',
                'string',
                Rule::unique('mass_productions')->where(function ($query) use ($request) {
                    return $query->where('furnace', $request->furnace);
                }),
            ],
            'furnace' => 'nullable|string',
            'batch_cycle_no' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'cycle_no' => 'nullable|string',
            'pattern_no' => 'nullable|integer',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'date_start' => 'nullable|date',
            'time_start' => 'nullable',
            'date_finished' => 'nullable|date',
            'time_finished' => 'nullable',
            'partial_no' => 'nullable|integer',
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'mias_emp' => 'nullable|string',
            'factor_emp' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9_5' => 'nullable|json',
            'grand_total_weight' => 'nullable|numeric',
            'grand_total_quantity' => 'nullable|integer',
            'layer_1_serial' => 'nullable|integer',
            'layer_2_serial' => 'nullable|integer',
            'layer_3_serial' => 'nullable|integer',
            'layer_4_serial' => 'nullable|integer',
            'layer_5_serial' => 'nullable|integer',
            'layer_6_serial' => 'nullable|integer',
            'layer_7_serial' => 'nullable|integer',
            'layer_8_serial' => 'nullable|integer',
            'layer_9_serial' => 'nullable|integer',
            'layer_9_5_serial' => 'nullable|integer',
            'mpi_sample_qty' => 'nullable|integer',
            'layer_1_format_type' => 'nullable|string',
            'layer_2_format_type' => 'nullable|string',
            'layer_3_format_type' => 'nullable|string',
            'layer_4_format_type' => 'nullable|string',
            'layer_5_format_type' => 'nullable|string',
            'layer_6_format_type' => 'nullable|string',
            'layer_7_format_type' => 'nullable|string',
            'layer_8_format_type' => 'nullable|string',
            'layer_9_format_type' => 'nullable|string',
            'layer_9_5_format_type' => 'nullable|string',
        ]);

        return MassProduction::create($validated);
    }


    public function show($id)
    {
        return MassProduction::findOrFail($id);
    }

    public function update(Request $request, $furnace, $massProd)
    {
        // Find the record by composite key: furnace + mass_prod
        $production = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->firstOrFail();

        // Validate only fields that may be sent in the payload
        $validated = $request->validate([
            'estimated_completion' => 'nullable|date',
            'mass_prod' => [
                'required',
                'string',
                Rule::unique('mass_productions')
                    ->ignore($production->id)
                    ->where(fn ($query) => $query->where('furnace', $request->furnace ?? $furnace)),
            ],
            'furnace' => 'nullable|string',
            'batch_cycle_no' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'cycle_no' => 'nullable|string',
            'pattern_no' => 'nullable|integer',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'date_start' => 'nullable|date',
            'time_start' => 'nullable',
            'date_finished' => 'nullable|date',
            'time_finished' => 'nullable',
            'partial_no' => 'nullable|integer',
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'mias_emp' => 'nullable|string',
            'factor_emp' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9_5' => 'nullable|json',
            'grand_total_weight' => 'nullable|numeric',
            'grand_total_quantity' => 'nullable|integer',
            'layer_1_serial' => 'nullable|integer',
            'layer_2_serial' => 'nullable|integer',
            'layer_3_serial' => 'nullable|integer',
            'layer_4_serial' => 'nullable|integer',
            'layer_5_serial' => 'nullable|integer',
            'layer_6_serial' => 'nullable|integer',
            'layer_7_serial' => 'nullable|integer',
            'layer_8_serial' => 'nullable|integer',
            'layer_9_serial' => 'nullable|integer',
            'layer_9_5_serial' => 'nullable|integer',
            'mpi_sample_qty' => 'nullable|integer',
            'layer_1_format_type' => 'nullable|string',
            'layer_2_format_type' => 'nullable|string',
            'layer_3_format_type' => 'nullable|string',
            'layer_4_format_type' => 'nullable|string',
            'layer_5_format_type' => 'nullable|string',
            'layer_6_format_type' => 'nullable|string',
            'layer_7_format_type' => 'nullable|string',
            'layer_8_format_type' => 'nullable|string',
            'layer_9_format_type' => 'nullable|string',
            'layer_9_5_format_type' => 'nullable|string',
        ]);

        // Update only the fields sent in the payload
        $production->update($validated);

        return $production;
    }

    public function destroy($id)
    {
        $production = MassProduction::findOrFail($id);
        $production->delete();

        return response()->json(['message' => 'Deleted'], 204);
    }

    public function allMassProductionWithDuplicates()
    {
        return MassProduction::orderBy('created_at', 'desc')->get();
    }

    public function getByFurnaceAndMassProd($furnace, $massprod)
    {
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "No record found for Furnace: {$furnace}, Mass Production: {$massprod}."
            ], 404);
        }

        return response()->json($record);
    }

    public function updateByFurnaceAndMassProd(Request $request, $furnace, $massprod)
    {
        // Find the record by composite key: furnace + mass_prod
        $production = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$production) {
            return response()->json([
                'message' => "No record found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Validate incoming data
        $validated = $request->validate([
            'estimated_completion' => 'nullable|date',
            'furnace' => 'nullable|string',
            'batch_cycle_no' => 'nullable|string',
            'machine_no' => 'nullable|string',
            'cycle_no' => 'nullable|string',
            'pattern_no' => 'nullable|integer',
            'cycle_pattern' => 'nullable|string',
            'current_pattern' => 'nullable|string',
            'date_start' => 'nullable|date',
            'time_start' => 'nullable',
            'date_finished' => 'nullable|date',
            'time_finished' => 'nullable',
            'partial_no' => 'nullable|integer',
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'mias_emp' => 'nullable|string',
            'factor_emp' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9_5' => 'nullable|json',
            'grand_total_weight' => 'nullable|numeric',
            'grand_total_quantity' => 'nullable|integer',
            'layer_1_serial' => 'nullable|integer',
            'layer_2_serial' => 'nullable|integer',
            'layer_3_serial' => 'nullable|integer',
            'layer_4_serial' => 'nullable|integer',
            'layer_5_serial' => 'nullable|integer',
            'layer_6_serial' => 'nullable|integer',
            'layer_7_serial' => 'nullable|integer',
            'layer_8_serial' => 'nullable|integer',
            'layer_9_serial' => 'nullable|integer',
            'layer_9_5_serial' => 'nullable|integer',
            'mpi_sample_qty' => 'nullable|integer',
            'layer_1_format_type' => 'nullable|string',
            'layer_2_format_type' => 'nullable|string',
            'layer_3_format_type' => 'nullable|string',
            'layer_4_format_type' => 'nullable|string',
            'layer_5_format_type' => 'nullable|string',
            'layer_6_format_type' => 'nullable|string',
            'layer_7_format_type' => 'nullable|string',
            'layer_8_format_type' => 'nullable|string',
            'layer_9_format_type' => 'nullable|string',
            'layer_9_5_format_type' => 'nullable|string',
        ]);

        // Update record
        $production->update($validated);

        return response()->json([
            'message' => "Mass Production record updated successfully for Furnace: {$furnace}, Mass Production: {$massprod}.",
            'data' => $production,
        ]);
    }


    public function uploadGraphs(Request $request, $furnace, $massprod)
    {
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "No record found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Folder name format: "K40 101"
        $folderName = "{$furnace} {$massprod}";
        $baseDir = public_path("htgraphs/{$folderName}");
        $folders = ['cycle', 'actual', 'standard'];

        foreach ($folders as $folder) {
            $path = "{$baseDir}/{$folder}";
            if (!file_exists($path)) mkdir($path, 0777, true);
        }

        foreach (['cycle_graph' => 'cycle', 'actual_graph' => 'actual'] as $inputName => $folder) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                if ($file->isValid()) {
                    $file->move("{$baseDir}/{$folder}", 'graph.png');
                }
            }
        }

        if ($request->has(['pattern_no', 'furnace_no'])) {
            $patternNo = $request->input('pattern_no');
            $furnaceNo = $request->input('furnace_no');

            \Log::info('Attempting to fetch HT graph pattern', [
                'pattern_no' => $patternNo,
                'furnace_no' => $furnaceNo
            ]);

            $pattern = \App\Models\HtGraphPatterns::where('pattern_no', $patternNo)
                ->where('furnace_no', $furnaceNo)
                ->first();

            if (!$pattern) {
                \Log::warning('No HT graph pattern found', [
                    'pattern_no' => $patternNo,
                    'furnace_no' => $furnaceNo
                ]);
            } else {
                $sourceDir = public_path("htgraph_patterns");
                \Log::info('Looking for pattern files in directory', ['sourceDir' => $sourceDir]);

                $files = glob("{$sourceDir}/pattern_{$patternNo}_{$furnaceNo}.{png,jpg,jpeg}", GLOB_BRACE);

                if (count($files) === 0) {
                    \Log::warning('No pattern files found matching glob', [
                        'glob' => "{$sourceDir}/pattern_{$patternNo}_{$furnaceNo}.{png,jpg,jpeg}"
                    ]);
                } else {
                    \Log::info('Pattern files found', ['files' => $files]);
                    $destination = "{$baseDir}/standard/graph.png";

                    if (copy($files[0], $destination)) {
                        \Log::info('Standard graph copied successfully', [
                            'source' => $files[0],
                            'destination' => $destination
                        ]);
                    } else {
                        \Log::error('Failed to copy standard graph', [
                            'source' => $files[0],
                            'destination' => $destination
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'message' => "Graphs uploaded successfully for {$folderName}.",
        ]);
    }


    public function getLayerModel($furnace, $massprod, $layerNumber)
    {
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "Record not found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Normalize layer number (e.g., 9.5 → layer_9_5)
        $layerColumn = 'layer_' . str_replace('.', '_', $layerNumber);

        if (!$record) {
            return response()->json([
                'message' => "No record found for furnace '{$furnace}' and mass production '{$massprod}'"
            ], 404);
        }

        Log::info('Record fetched: ', $record->toArray());
        Log::info('Layer column: ' . $layerColumn);

        if (!isset($record->$layerColumn)) {
            return response()->json([
                'message' => "No valid data in Layer {$layerNumber}.",
            ], 404);
        }

        $layerData = json_decode($record->$layerColumn, true);

        if (empty($layerData) || !is_array($layerData)) {
            return response()->json([
                'message' => "No valid data in Layer {$layerNumber}.",
            ], 404);
        }

        $layerData = json_decode($record->$layerColumn, true);

        if (empty($layerData) || !is_array($layerData)) {
            return response()->json([
                'message' => "No valid data in Layer {$layerNumber}.",
            ], 404);
        }

        foreach ($layerData as $row) {
            if (($row['rowTitle'] ?? null) === 'MODEL:') {
                return response()->json([
                    'model' => $row['data']['A'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => "MODEL row not found in Layer {$layerNumber}.",
        ], 404);
    }

    public function getLayerLotno($furnace, $massprod, $layerNumber)
    {
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "Record not found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Normalize layer number (e.g., 9.5 → layer_9_5)
        $layerColumn = 'layer_' . str_replace('.', '_', $layerNumber);

        // Check if the layer column exists and is not null
        if (!isset($record->$layerColumn) || $record->$layerColumn === null) {
            return response()->json([
                'message' => "Layer {$layerNumber} not found for this record.",
            ], 404);
        }

        $layerData = json_decode($record->$layerColumn, true);

        if (empty($layerData) || !is_array($layerData)) {
            return response()->json([
                'message' => "No valid data in Layer {$layerNumber}.",
            ], 404);
        }

        // Search for row with "LT. No.:"
        foreach ($layerData as $row) {
            if (($row['rowTitle'] ?? null) === 'LT. No.:') {
                return response()->json([
                    'lotno' => $row['data']['A'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => "LT. No. row not found in Layer {$layerNumber}.",
        ], 404);
    }


    public function getAllHTCompletedLayers($furnace, $massprod)
    {
        // Fetch record using composite key
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "Record not found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Define possible layers
        $layers = [
            'layer_1'   => '1',
            'layer_2'   => '2',
            'layer_3'   => '3',
            'layer_4'   => '4',
            'layer_5'   => '5',
            'layer_6'   => '6',
            'layer_7'   => '7',
            'layer_8'   => '8',
            'layer_9'   => '9',
            'layer_9_5' => '9.5',
        ];

        $completed = [];

        // Check layers in MassProduction
        foreach ($layers as $column => $label) {
            $value = $record->$column ?? null;
            if (!empty($value) && $value !== 'null') {
                $decoded = json_decode($value, true);
                if (!empty($decoded)) {
                    $completed[] = $label;
                }
            }
        }

        // Fetch completed layers from GbdpSecondHeatTreatment (scoped by furnace + mass_prod)
        $gbdpLayers = GbdpSecondHeatTreatment::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->toArray();

        // Merge, remove duplicates, and sort numerically
        $allCompletedLayers = array_unique(array_merge($completed, $gbdpLayers));
        sort($allCompletedLayers, SORT_NUMERIC);

        return response()->json([
            'completed_layers' => $allCompletedLayers,
        ]);
    }

    public function getAllCompletedLayersFilmPaste($furnace, $massprod)
    {
        // Fetch record using composite key
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "Record not found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Define possible layers
        $layers = [
            'layer_1'   => '1',
            'layer_2'   => '2',
            'layer_3'   => '3',
            'layer_4'   => '4',
            'layer_5'   => '5',
            'layer_6'   => '6',
            'layer_7'   => '7',
            'layer_8'   => '8',
            'layer_9'   => '9',
            'layer_9_5' => '9.5',
        ];

        $completed = [];

        // Check layers in MassProduction
        foreach ($layers as $column => $label) {
            $value = $record->$column ?? null;
            if (!empty($value) && $value !== 'null') {
                $decoded = json_decode($value, true);
                if (!empty($decoded)) {
                    $completed[] = $label;
                }
            }
        }

        // Fetch completed layers from GbdpSecondHeatTreatment (scoped by furnace + mass_prod)
        $gbdpLayers = GbdpSecondHeatTreatment::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->toArray();

        // Merge, remove duplicates, and sort numerically
        $allCompletedLayers = array_unique(array_merge($completed, $gbdpLayers));

        // Fetch layers that exist in Coating and Second Coating
        $coatingLayers = Coating::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->toArray();

        $secondCoatingLayers = GbdpSecondCoating::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->toArray();

        // Exclude layers that exist in coatings
        $unavailableLayers = array_merge($coatingLayers, $secondCoatingLayers);
        $availableLayers = array_diff($allCompletedLayers, $unavailableLayers);

        // Sort numerically
        sort($availableLayers, SORT_NUMERIC);

        return response()->json([
            'completed_layers' => array_values($availableLayers),
        ]);
    }

    public function getAllCompletedLayersCoating($furnace, $massprod)
    {
        // Fetch MassProduction record
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => "Record not found for Furnace: {$furnace}, Mass Production: {$massprod}.",
            ], 404);
        }

        // Define possible layers
        $layers = [
            'layer_1'   => '1',
            'layer_2'   => '2',
            'layer_3'   => '3',
            'layer_4'   => '4',
            'layer_5'   => '5',
            'layer_6'   => '6',
            'layer_7'   => '7',
            'layer_8'   => '8',
            'layer_9'   => '9',
            'layer_9_5' => '9.5',
        ];

        // Collect completed layers from MassProduction
        $completed = [];
        foreach ($layers as $column => $label) {
            $value = $record->$column ?? null;
            if (!empty($value) && $value !== 'null') {
                $decoded = json_decode($value, true);
                if (!empty($decoded)) {
                    $completed[] = $label;
                }
            }
        }

        // Merge with completed layers from GbdpSecondHeatTreatment
        $heatTreatmentLayers = GbdpSecondHeatTreatment::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->toArray();

        $allCompletedLayers = array_unique(array_merge($completed, $heatTreatmentLayers));

        // Fetch layers that exist in FilmPastingData
        $filmPastingLayers = FilmPastingData::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->toArray();

        // Remove layers that already exist in FilmPastingData
        $availableLayers = array_diff($allCompletedLayers, $filmPastingLayers);

        // Sort numerically
        sort($availableLayers, SORT_NUMERIC);

        return response()->json([
            'completed_layers' => array_values($availableLayers),
        ]);
    }


    public function getAllCoatingCompleteLayers($furnace, $massprod)
    {
        // Get layers from gbdp_second_coatings
        $secondLayers = GbdpSecondCoating::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->whereNotNull('layer')
            ->pluck('layer')
            ->map(fn($layer) => (string) $layer);

        // Get layers from coatings
        $coatingLayers = Coating::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->whereNotNull('layer')
            ->pluck('layer')
            ->map(fn($layer) => (string) $layer);

        // Get layers from film_pasting_data
        $filmLayers = FilmPastingData::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->whereNotNull('layer')
            ->pluck('layer')
            ->map(fn($layer) => (string) $layer);

        // Merge, remove duplicates, sort, and reindex
        $layers = $secondLayers
            ->merge($coatingLayers)
            ->merge($filmLayers)
            ->unique()
            ->sort()
            ->values();

        return response()->json([
            'completed_layers' => $layers
        ]);
    }


    public function getAllSecondHTCompletedLayers($furnace, $massprod)
    {
        // Fetch layers from gbdp_second_heat_treatments table for specific furnace + mass_prod
        $gbdpLayers = GbdpSecondHeatTreatment::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->filter() // remove nulls
            ->map(fn($layer) => (string) $layer) // cast to string for consistency
            ->values(); // reindex array

        return response()->json([
            '2nd_gbdp_layers' => $gbdpLayers,
        ]);
    }


    public function getAllFilmPastingCompletedLayers($furnace, $massprod)
    {
        // Fetch film pasting layers for specific furnace + mass production
        $filmPastingLayers = FilmPastingData::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->pluck('layer')
            ->filter() // remove nulls
            ->map(fn($layer) => (string) $layer) // cast to string
            ->values(); // reindex array

        return response()->json([
            'film_pasting_layers' => $filmPastingLayers,
        ]);
    }


    public function getLayerDataBySerial($furnace, $massprod, $serial)
    {
        // Find record by composite key: furnace + mass_prod
        $production = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$production) {
            return response()->json([
                'message' => "Mass Production record not found for furnace '{$furnace}' and mass production '{$massprod}'.",
            ], 404);
        }

        // Map serial columns to layer columns
        $layers = [
            'layer_1_serial'   => 'layer_1',
            'layer_2_serial'   => 'layer_2',
            'layer_3_serial'   => 'layer_3',
            'layer_4_serial'   => 'layer_4',
            'layer_5_serial'   => 'layer_5',
            'layer_6_serial'   => 'layer_6',
            'layer_7_serial'   => 'layer_7',
            'layer_8_serial'   => 'layer_8',
            'layer_9_serial'   => 'layer_9',
            'layer_9_5_serial' => 'layer_9_5',
        ];

        foreach ($layers as $serialColumn => $layerColumn) {
            if ($production->$serialColumn == $serial) {
                return response()->json([
                    'serial_column' => $serialColumn,
                    'layer_column'  => $layerColumn,
                    'layer_data'    => $production->$layerColumn ? json_decode($production->$layerColumn, true) : null,
                ]);
            }
        }

        return response()->json([
            'message' => "No layer data found for serial '{$serial}' in furnace '{$furnace}' mass production '{$massprod}'.",
        ], 404);
    }

    public function getLayerDataByLayerNo($furnace, $massprod, $layer)
    {
        Log::info('getLayerDataByLayerNo called', compact('furnace','massprod','layer'));

        $production = MassProduction::where('furnace', trim($furnace))
            ->where('mass_prod', trim($massprod))
            ->first();

        if (!$production) {
            Log::warning('Record not found', compact('furnace','massprod'));
            return response()->json([
                'message' => "Mass Production record not found for furnace '{$furnace}' and mass production '{$massprod}'.",
            ], 404);
        }

        $layerMap = [
            '1'   => 'layer_1',
            '2'   => 'layer_2',
            '3'   => 'layer_3',
            '4'   => 'layer_4',
            '5'   => 'layer_5',
            '6'   => 'layer_6',
            '7'   => 'layer_7',
            '8'   => 'layer_8',
            '9'   => 'layer_9',
            '9.5' => 'layer_9_5',
        ];

        $layer = (string)$layer; // force string
        if (!array_key_exists($layer, $layerMap)) {
            Log::warning('Invalid layer', compact('layer'));
            return response()->json([
                'message' => "Invalid layer number: {$layer}",
            ], 400);
        }

        $layerColumn = $layerMap[$layer];
        $layerData = $production->$layerColumn ? json_decode($production->$layerColumn, true) : null;

        if (empty($layerData) || !is_array($layerData)) {
            Log::warning('Layer data empty', compact('layerColumn','layerData'));
            return response()->json([
                'message' => "No valid data found for layer {$layer} in furnace '{$furnace}' mass production '{$massprod}'.",
            ], 404);
        }

        return response()->json([
            'layer_column' => $layerColumn,
            'layer_data'   => $layerData,
        ]);
    }


    public function smpDataSummary($furnace, $massprod)
    {
        $flattenedLayers = app(\App\Services\SmpDataService::class)->getFlattenedData($furnace, $massprod);
        $formatType = reset($flattenedLayers)['format_type'] ?? null;

        return response()->json([
            'layersData' => $flattenedLayers,
            'format_type' => $formatType,
        ]);
    }

    public function massProductionMonitoring($furnace, $massprod)
    {
        // Fetch all mass production names for dropdown — limited to this furnace
        $massProdNames = MassProduction::where('furnace', $furnace)
            ->pluck('mass_prod')
            ->toArray();

        // Fetch the selected mass production row
        $prod = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$prod) {
            return response()->json([
                'success' => false,
                'message' => 'Mass production not found',
                'massProdNames' => $massProdNames,
                'data' => [],
            ]);
        }

        $layers = [];

        // Layers 1 to 9
        for ($i = 1; $i <= 9; $i++) {
            $layer_json = $prod->{'layer_'.$i};
            $layer_type = $prod->{'layer_'.$i.'_format_type'};

            $layerStatus = [
                'type' => $layer_type,
                'heat_treatment_completed' => false,
                'coating_completed' => false,
                'mpi_completed' => false,
            ];

            if ($layer_type === 'Normal') {
                $layerStatus['heat_treatment_completed'] = !empty($layer_json);
                $layerStatus['coating_completed'] = Coating::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer', $i)
                    ->exists();
                $layerStatus['mpi_completed'] = TPMData::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer_no', $i)
                    ->exists();

            } elseif ($layer_type === '1st and 2nd GBDP') {
                $layerStatus['heat_treatment_completed'] = GbdpSecondHeatTreatment::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer', $i)
                    ->exists();
                $layerStatus['coating_completed'] = GbdpSecondCoating::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer', $i)
                    ->exists();
                $layerStatus['mpi_completed'] = TPMData::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer_no', $i)
                    ->exists();

            } elseif ($layer_type === 'Film Pasting') {
                $layerStatus['heat_treatment_completed'] = !empty($layer_json);
                $layerStatus['coating_completed'] = FilmPastingData::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer', $i)
                    ->exists();
                $layerStatus['mpi_completed'] = TPMData::where('furnace', $furnace)
                    ->where('mass_prod', $prod->mass_prod)
                    ->where('layer_no', $i)
                    ->exists();
            }

            $layers[$i] = $layerStatus;
        }

        // Layer 9.5
        $layer_9_5_json = $prod->layer_9_5;
        $layer_9_5_type = $prod->layer_9_5_format_type;

        $layers['9.5'] = [
            'type' => $layer_9_5_type,
            'heat_treatment_completed' => !empty($layer_9_5_json),
            'coating_completed' => Coating::where('furnace', $furnace)
                ->where('mass_prod', $prod->mass_prod)
                ->where('layer', '9.5')
                ->exists(),
            'mpi_completed' => TPMData::where('furnace', $furnace)
                ->where('mass_prod', $prod->mass_prod)
                ->where('layer_no', '9.5')
                ->exists(),
        ];

        return response()->json([
            'success' => true,
            'massProdNames' => $massProdNames,
            'data' => [
                [
                    'furnace' => $furnace,
                    'mass_prod' => $prod->mass_prod,
                    'layers' => $layers,
                ]
            ],
        ]);
    }

    public function breakLotMonitoring($furnace, $massprod)
    {
        // Fetch the mass production record
        $record = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->first();

        if (!$record) {
            return response()->json(['error' => 'Mass production not found'], 404);
        }

        $layers = [];
        $boxLetters = ['A','B','C','D','E','F','G','H','J','K'];

        // Fetch all excess layers for this furnace + massprod in one query
        $excessLayers = ExcessLayers::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->get()
            ->keyBy('layer'); // key by layer number for easy access

        // Loop through each main layer_1 to layer_9
        for ($i = 1; $i <= 9; $i++) {
            $layerKey = "layer_{$i}";
            $mainLayerData = $record->$layerKey ? json_decode($record->$layerKey, true) : [];

            // If there is an excess layer for this layer, merge its data
            $excessLayerData = $excessLayers->has($i) ? $excessLayers[$i]->layer_data : [];

            // Merge main layer data and excess layer data
            $combinedData = array_merge($mainLayerData ?? [], $excessLayerData ?? []);

            $availableBoxes = [];

            if (!empty($combinedData)) {
                foreach ($boxLetters as $box) {
                    $hasData = false;

                    foreach ($combinedData as $row) {
                        if (!empty($row['data'][$box])) {
                            $hasData = true;
                            break;
                        }
                    }

                    if (!$hasData) {
                        $availableBoxes[] = $box;
                    }
                }
            } else {
                // Entire layer empty → all boxes are available
                $availableBoxes = $boxLetters;
            }

            $layers[$layerKey] = $availableBoxes;
        }

        return response()->json([
            'furnace' => $furnace,
            'massprod' => $massprod,
            'available_slots' => $layers
        ]);
    }


    public function mergeMainLayer(Request $request, $furnace, $massProd)
    {
        $validated = $request->validate([
            'layer' => 'required|string', // e.g., layer_1, layer_2
            'layer_data' => 'required|array', // decoded array from Vue
        ]);

        // Fetch the mass production record
        $massProdRecord = MassProduction::where([
            'furnace' => $furnace,
            'mass_prod' => $massProd,
        ])->firstOrFail();

        $layerColumn = $validated['layer'];
        $newLayerData = $validated['layer_data'];

        // Check if the column already has data
        if ($massProdRecord->$layerColumn) {
            $existingData = json_decode($massProdRecord->$layerColumn, true);

            // Merge box data per rowTitle
            $mergedData = [];
            foreach ($existingData as $row) {
                $mergedData[$row['rowTitle']] = $row['data'];
            }
            foreach ($newLayerData as $row) {
                $title = $row['rowTitle'];
                if (!isset($mergedData[$title])) {
                    $mergedData[$title] = $row['data'];
                } else {
                    $mergedData[$title] = array_merge($mergedData[$title], $row['data']);
                }
            }

            $finalData = [];
            foreach ($mergedData as $rowTitle => $boxes) {
                $finalData[] = [
                    'rowTitle' => $rowTitle,
                    'data' => $boxes
                ];
            }

            $massProdRecord->$layerColumn = json_encode($finalData);
        } else {
            // No existing data, just insert
            $massProdRecord->$layerColumn = json_encode($newLayerData);
        }

        $massProdRecord->save();

        return response()->json($massProdRecord, 200);
    }

    public function excessLayerList($furnace, $massprod, $excessData)
    {
        $excessBoxes = $this->parseBoxRange($excessData);
        $allBoxes = ['A','B','C','D','E','F','G','H','J','K'];

        // --- Fetch main mass production row ---
        $mp = MassProduction::where('furnace', $furnace)
                ->where('mass_prod', $massprod)
                ->first();

        if (!$mp) {
            return response()->json(['message' => 'Mass production not found'], 404);
        }

        // --- Fetch all excess layers for this mass production ---
        $excessLayers = ExcessLayers::where('furnace', $furnace)
                            ->where('mass_prod', $massprod)
                            ->get()
                            ->keyBy('layer'); // key by layer number for easier lookup

        $availableLayers = [];

        for ($i = 1; $i <= 9; $i++) {
            $layerName = "layer_$i";
            $layerData = is_array($mp[$layerName]) ? $mp[$layerName] : json_decode($mp[$layerName], true) ?? [];

            // Determine filled boxes from main layer
            $qtyRow = collect($layerData)->firstWhere('rowTitle', 'QTY (PCS):')['data'] ?? [];
            $filledBoxes = [];
            foreach ($allBoxes as $box) {
                if (isset($qtyRow[$box]) && $qtyRow[$box] !== null && $qtyRow[$box] !== '') {
                    $filledBoxes[] = $box;
                }
            }

            // --- Include any previously saved excess boxes ---
            if (isset($excessLayers[$i])) {
                $excessLayerData = is_array($excessLayers[$i]->layer_data)
                    ? $excessLayers[$i]->layer_data
                    : json_decode($excessLayers[$i]->layer_data, true) ?? [];
                $excessQtyRow = collect($excessLayerData)->firstWhere('rowTitle', 'QTY (PCS):')['data'] ?? [];
                foreach ($allBoxes as $box) {
                    if (isset($excessQtyRow[$box]) && $excessQtyRow[$box] !== null && $excessQtyRow[$box] !== '') {
                        $filledBoxes[] = $box;
                    }
                }
                $filledBoxes = array_unique($filledBoxes);
            }

            $availableBoxes = array_diff($allBoxes, $filledBoxes);

            if (empty($filledBoxes)) {
                // Entire layer empty → all boxes available
                $availableLayers[$i] = $allBoxes;
            } elseif (!empty($availableBoxes)) {
                // Partially filled → check if excess boxes fit
                $fits = empty(array_diff($excessBoxes, $availableBoxes));
                if ($fits) {
                    $availableLayers[$i] = $availableBoxes;
                }
            }
            // Fully filled layers are automatically ignored
        }

        return response()->json([
            'availableLayers' => $availableLayers
        ]);
    }

    private function parseBoxRange(string $range): array
    {
        $letters = range('A', 'K'); // All possible boxes
        if (strpos($range, '-') !== false) {
            [$start, $end] = explode('-', $range);
            $startIndex = array_search($start, $letters);
            $endIndex   = array_search($end, $letters);
            return array_slice($letters, $startIndex, $endIndex - $startIndex + 1);
        }
        return [$range]; // Single box
    }

    public function generateMonthlySummary(Request $request)
    {
        $month = $request->input('month'); // 1-12
        $year  = $request->input('year');  // e.g., 2025

        $records = MassProduction::whereMonth('estimated_completion', $month)
                                ->whereYear('estimated_completion', $year)
                                ->get();

        if ($records->isEmpty()) {
            return response()->json([]);
        }

        $excelData = [];

        $layerKeys = [
            'layer_1', 'layer_2', 'layer_3', 'layer_4',
            'layer_5', 'layer_6', 'layer_7', 'layer_8',
            'layer_9', 'layer_9_5'
        ];

        $layerLabels = [
            'layer_1'   => '1st',
            'layer_2'   => '2nd',
            'layer_3'   => '3rd',
            'layer_4'   => '4th',
            'layer_5'   => '5th',
            'layer_6'   => '6th',
            'layer_7'   => '7th',
            'layer_8'   => '8th',
            'layer_9'   => '9th',
            'layer_9_5' => '9.5th',
        ];

        foreach ($records as $record) {
            $baseRow = [
                'MACHINE NO'  => $record->machine_no,
                'CYCLE NO'    => $record->cycle_no,
                'PTN'         => $record->pattern_no,
                'DATE LOAD'   => $record->date_start,
                'DATE UNLOAD' => $record->date_finished,
            ];

            foreach ($layerKeys as $layerKey) {
                if (!empty($record->$layerKey)) {
                    $layerJson = json_decode($record->$layerKey, true);

                    $modelRow = collect($layerJson)->first(fn($r) => $r['rowTitle'] === 'MODEL:');
                    $lotRow   = collect($layerJson)->first(fn($r) => $r['rowTitle'] === 'LT. No.:');
                    $qtyRow   = collect($layerJson)->first(fn($r) => $r['rowTitle'] === 'TOTAL QTY');
                    $wtRow    = collect($layerJson)->first(fn($r) => $r['rowTitle'] === 'WT (KG):');

                    $beforeGbdpWt = $wtRow ? array_sum($wtRow['data']) : 0;

                    $modelName = $modelRow ? $modelRow['data']['A'] : null;

                    // Lookup HT Mass Pro Data for this model
                    $htData = HtMassProData::where('model_name', $modelName)->latest('id')->first();

                    $productWeight = $htData ? $htData->product_weight : 0;
                    $secondSl      = $htData ? $htData->{'2nd_sl'} : 0;
                    $htQty         = $htData ? $htData->qty : 0;
                    $qty           = $qtyRow ? $qtyRow['data']['A'] : 0;

                    // AFTER GBDP WT calculation
                    $afterGbdpWt = ($qty * $productWeight * $secondSl) / 1_000_000;

                    // EQUIVALENT LOTS calculation
                    $equivalentLots = $qty * $htQty;

                    $excelData[] = array_merge($baseRow, [
                        'MODEL NAME'       => $modelName,
                        'LOT'              => $lotRow   ? $lotRow['data']['A'] : null,
                        'QTY'              => $qty,
                        'BEFORE GBDP WT'   => $beforeGbdpWt,
                        'AFTER GBDP WT'    => $afterGbdpWt,
                        'LAYER'            => $layerLabels[$layerKey] ?? null,
                        'EQUIVALENT LOTS'  => $equivalentLots,
                    ]);
                }
            }
        }

        return $excelData;
    }


    public function getMonthlySummary(Request $request)
    {
        $data = $this->generateMonthlySummary($request);
        return response()->json($data);
    }

    public function getGrandTotalWeight(Request $request)
    {
        $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
        ]);

        $record = MassProduction::where('mass_prod', $request->mass_prod)
            ->where('furnace', $request->furnace)
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Mass production record not found'
            ], 404);
        }

        $layers = [
            'layer_1',
            'layer_2',
            'layer_3',
            'layer_4',
            'layer_5',
            'layer_6',
            'layer_7',
            'layer_8',
            'layer_9',
            'layer_9_5',
        ];

        $grandTotal = 0;

        foreach ($layers as $layerColumn) {
            if (empty($record->$layerColumn)) {
                continue;
            }

            $layerData = json_decode($record->$layerColumn, true);

            if (!is_array($layerData)) {
                continue;
            }

            foreach ($layerData as $row) {
                if (($row['rowTitle'] ?? '') === 'WT (KG):') {
                    $weights = $row['data'] ?? [];

                    foreach ($weights as $value) {
                        $grandTotal += floatval($value);
                    }

                    break; // no need to scan other rows
                }
            }
        }

        return response()->json([
            'mass_prod'   => $request->mass_prod,
            'furnace'     => $request->furnace,
            'grand_total' => round($grandTotal, 3),
        ]);
    }

    public function deleteLayerData(Request $request)
    {
        $massProd = $request->massprod;
        $furnace  = $request->furnace;
        $layer    = $request->layer;

        \Log::info('DeleteLayerData called', [
            'massProd' => $massProd,
            'furnace'  => $furnace,
            'layer'    => $layer
        ]);

        // Determine mass production column
        $column = $layer === '9.5' ? 'layer_9_5' : 'layer_' . $layer;

        // --- 1. Fetch the mass production row ---
        $mp = MassProduction::where('mass_prod', $massProd)
                            ->where('furnace', $furnace)
                            ->first();

        if (!$mp) {
            \Log::error('Mass production not found', ['massProd' => $massProd, 'furnace' => $furnace]);
            return response()->json(['success' => false, 'message' => 'Mass Production not found'], 404);
        }

        // --- 2. Decode layer JSON ---
        $layerData = json_decode($mp->$column, true);

        $targetModel = null;
        $targetLot   = null;

        if (!empty($layerData)) {
            foreach ($layerData as $row) {
                if ($row['rowTitle'] === 'MODEL:') {
                    $targetModel = $row['data']['A'] ?? null; // Take A as reference for model
                }
                if ($row['rowTitle'] === 'LT. No.:') {
                    $targetLot = $row['data']['A'] ?? null; // Take A as reference for lot
                }
            }
        }

        // --- 3. Null the mass production layer ---
        $mp->$column = null;
        $mp->save();

        \Log::info('Mass production layer nulled', [
            'column' => $column,
            'mass_prod_id' => $mp->id
        ]);

        // --- 4. Delete matching excess layer rows ---
        $deletedRows = 0;
        if ($targetModel && $targetLot) {
            $excessLayers = ExcessLayers::where('mass_prod', $massProd)
                                        ->where('furnace', $furnace)
                                        ->get();

            \Log::info('Excess layers fetched', ['count' => $excessLayers->count()]);

            foreach ($excessLayers as $excess) {
                $layerDataExcess = is_array($excess->layer_data) ? $excess->layer_data : json_decode($excess->layer_data, true);

                $shouldDelete = false;

                foreach ($layerDataExcess as $row) {
                    if ($row['rowTitle'] === 'MODEL:' && in_array($targetModel, $row['data'], true)) {
                        // Check if corresponding LT. No. exists in same row_data
                        foreach ($layerDataExcess as $rowLot) {
                            if ($rowLot['rowTitle'] === 'LT. No.:' && in_array($targetLot, $rowLot['data'], true)) {
                                $shouldDelete = true;
                                break 2; // Found both, no need to continue checking
                            }
                        }
                    }
                }

                if ($shouldDelete) {
                    $excess->delete();
                    $deletedRows++;
                    \Log::info('Excess layer row deleted', [
                        'excess_id' => $excess->id,
                        'mass_prod' => $massProd,
                        'furnace'   => $furnace
                    ]);
                }
            }
        }

        \Log::info('DeleteLayerData completed', [
            'mass_prod'   => $massProd,
            'furnace'     => $furnace,
            'targetModel' => $targetModel,
            'targetLot'   => $targetLot,
            'deletedRows' => $deletedRows
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Layer deleted successfully',
            'deleted_rows' => $deletedRows
        ]);
    }






}
