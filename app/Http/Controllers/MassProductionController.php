<?php

namespace App\Http\Controllers;

use App\Models\MassProduction;
use App\Models\GbdpSecondCoating;
use App\Models\GbdpSecondHeatTreatment;
use App\Models\FilmPastingData;
use App\Models\Coating;
use App\Models\TPMData;
use App\Models\TPMDataCategory;
use App\Models\HtMassProData;
use App\Models\TPMDataAggregateFunctions;
use App\Models\ReportData;
use App\Models\SmpData;
use App\Models\BreaklotInitialLot;
use App\Models\BreaklotCoating;
use App\Models\BreaklotSecondCoating;
use App\Models\BreaklotFilmpasting;
use App\Models\BreaklotInitialLotHt;
use App\Models\BreaklotSecondHeatTreatment;
use App\Models\BreaklotAddtnlFormatType;
use App\Models\ExcessLayers;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                    ->where(fn($query) => $query->where('furnace', $request->furnace ?? $furnace)),
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

            Log::info('Attempting to fetch HT graph pattern', [
                'pattern_no' => $patternNo,
                'furnace_no' => $furnaceNo
            ]);

            $pattern = \App\Models\HtGraphPatterns::where('pattern_no', $patternNo)
                ->where('furnace_no', $furnaceNo)
                ->first();

            if (!$pattern) {
                Log::warning('No HT graph pattern found', [
                    'pattern_no' => $patternNo,
                    'furnace_no' => $furnaceNo
                ]);
            } else {
                $sourceDir = public_path("htgraph_patterns");
                Log::info('Looking for pattern files in directory', ['sourceDir' => $sourceDir]);

                $files = glob("{$sourceDir}/pattern_{$patternNo}_{$furnaceNo}.{png,jpg,jpeg}", GLOB_BRACE);

                if (count($files) === 0) {
                    Log::warning('No pattern files found matching glob', [
                        'glob' => "{$sourceDir}/pattern_{$patternNo}_{$furnaceNo}.{png,jpg,jpeg}"
                    ]);
                } else {
                    Log::info('Pattern files found', ['files' => $files]);
                    $destination = "{$baseDir}/standard/graph.png";

                    if (copy($files[0], $destination)) {
                        Log::info('Standard graph copied successfully', [
                            'source' => $files[0],
                            'destination' => $destination
                        ]);
                    } else {
                        Log::error('Failed to copy standard graph', [
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


    public function getLayerModel(Request $request)
    {
        $request->validate([
            'furnace' => 'required',
            'massprod' => 'required',
            'layer' => 'required',
        ]);

        $furnace = $request->query('furnace');
        $massprod = $request->query('massprod');
        $layerNumber = $request->query('layer');

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

        foreach ($layerData as $index => $row) {

            $rowTitle = $row['rowTitle'] ?? null;

            if ($rowTitle === 'MODEL:') {

                $boxes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'];

                foreach ($boxes as $box) {
                    $value = $row['data'][$box] ?? null;

                    if (!empty($value)) {

                        Log::info("MODEL found in box {$box}.", [
                            'model' => $value
                        ]);

                        return response()->json([
                            'model' => $value,
                        ]);
                    }
                }

                break; // Row found but empty
            }
        }

        Log::warning("No MODEL row found in layer data", $layerData);

        return response()->json([
            'message' => "MODEL row not found in Layer {$layerNumber}.",
        ], 404);
    }

    public function getLayerLotno(Request $request)
    {

        //dd("getLayerLotno HIT");
        $request->validate([
            'furnace' => 'required',
            'massprod' => 'required',
            'layer' => 'required',
        ]);

        $furnace = $request->query('furnace');
        $massprod = $request->query('massprod');
        $layerNumber = $request->query('layer');

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
        if (!isset($record->$layerColumn)) {
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

                $boxes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'];

                foreach ($boxes as $box) {
                    $value = $row['data'][$box] ?? null;

                    if (!empty($value)) {
                        Log::info("LT. No. found in box {$box}.", ['lotno' => $value]);

                        return response()->json([
                            'lotno' => $value,
                        ]);
                    }
                }

                break; // Row found but no values inside boxes
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


    public function getLayerDataControlSheet(Request $request)
    {
        $request->validate([
            'furnace'  => 'required|string',
            'massprod' => 'required|string',
            'layer'    => 'required|string',
            'lotno'    => 'required|string',
            'model'    => 'required|string',
        ]);

        $production = MassProduction::where('furnace', $request->furnace)
            ->where('mass_prod', $request->massprod)
            ->first();

        if (!$production) {
            return response()->json([
                'message' => "Mass Production not found."
            ], 404);
        }

        $layer = $request->layer == '9.5' ? '9_5' : $request->layer;
        $layerColumn = 'layer_' . $layer;

        if (!isset($production->$layerColumn)) {
            return response()->json([
                'message' => "Layer '{$layerColumn}' not found."
            ], 404);
        }

        $layerData = json_decode($production->$layerColumn, true);

        if (!$layerData) {
            return response()->json([
                'message' => "Layer data is empty or invalid."
            ], 404);
        }

        // Extract needed rows
        $modelRow = collect($layerData)->firstWhere('rowTitle', 'MODEL:');
        $lotRow   = collect($layerData)->firstWhere('rowTitle', 'LT. No.:');
        $totalRow = collect($layerData)->firstWhere('rowTitle', 'TOTAL QTY');

        if (!$modelRow || !$lotRow || !$totalRow) {
            return response()->json([
                'message' => "Required rows not found."
            ], 404);
        }

        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'];
        $matchedKey = null;

        foreach ($letters as $key) {
            $modelMatch = $modelRow['data'][$key] ?? null;

            if ($modelMatch === $request->model) {
                $lotMatch = $lotRow['data'][$key] ?? null;

                if ($lotMatch === $request->lotno) {
                    $matchedKey = $key;
                    break;
                }
            }
        }

        $result = $matchedKey
            ? ($totalRow['data'][$matchedKey] ?? 0)
            : 0;

        return response()->json([
            'matched_key' => $matchedKey,
            'total_qty'   => $result,
        ]);
    }

    public function getLayerDataByLayerNo($furnace, $massprod, $layer)
    {
        Log::info('getLayerDataByLayerNo called', compact('furnace', 'massprod', 'layer'));

        $production = MassProduction::where('furnace', trim($furnace))
            ->where('mass_prod', trim($massprod))
            ->first();

        if (!$production) {
            Log::warning('Record not found', compact('furnace', 'massprod'));
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
            Log::warning('Layer data empty', compact('layerColumn', 'layerData'));
            return response()->json([
                'message' => "No valid data found for layer {$layer} in furnace '{$furnace}' mass production '{$massprod}'.",
            ], 404);
        }

        $layerLotLists = [];

        foreach ($layerData as $row) {
            if (($row['rowTitle'] ?? '') === 'LT. No.:') {
                $lots = $row['data'] ?? [];

                if (is_array($lots)) {
                    // keep only unique values, reindex
                    $layerLotLists = array_values(array_unique(array_filter($lots)));
                }
                break;
            }
        }

        $layerModelLists = [];

        foreach ($layerData as $row) {
            if (($row['rowTitle'] ?? '') === 'MODEL:') {
                $models = $row['data'] ?? [];

                if (is_array($models)) {
                    // keep only unique values, reindex
                    $layerModelLists = array_values(array_unique(array_filter($models)));
                }
                break;
            }
        }

        $layerLotModelPairs = [];

        $length = min(count($layerLotLists), count($layerModelLists));

        for ($i = 0; $i < $length; $i++) {
            $layerLotModelPairs[$layerLotLists[$i]] = $layerModelLists[$i];
        }

        // Fetch initial lot/model for this layer
        $initialLotHt = BreaklotInitialLotHt::where('furnace', $furnace)
            ->where('mass_prod', $massprod)
            ->where('layer', $layer)
            ->first();

        $initialLot = $initialLotHt->initial_lot ?? null;
        $initialModel = $initialLotHt->initial_model ?? null;

        return response()->json([
            'layer_column' => $layerColumn,
            'layer_data'   => $layerData,
            'layer_lot_lists'  => $layerLotLists,
            'lot_pairs' => $layerLotModelPairs,
            'initial_lot' => $initialLot,
            'initial_model' => $initialModel,
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

        // Helper: extract unique model + lot from JSON
        $extractModelLot = function ($layer_json) {
            $models = [];
            $lots = [];

            if ($layer_json) {
                $layerData = json_decode($layer_json, true);
                if ($layerData) {
                    foreach ($layerData as $row) {
                        if ($row['rowTitle'] === 'MODEL:') {
                            $models = array_unique(array_values($row['data']));
                        }
                        if ($row['rowTitle'] === 'LT. No.:') {
                            $lots = array_unique(array_values($row['data']));
                        }
                    }
                }
            }

            return [
                'models' => $models,
                'lots' => $lots,
            ];
        };

        // Process one layer (normal or breaklot)
        $processLayer = function ($layer_json, $layer_no, $layer_type) use ($furnace, $massprod, &$layers, $extractModelLot) {
            $modelLot = $extractModelLot($layer_json);
            $uniqueCount = count($modelLot['models']);

            $isBreaklot = $uniqueCount > 1 || count($modelLot['lots']) > 1;

            if (!$isBreaklot) {
                // Normal layer
                $layerStatus = [
                    'layer_no' => $layer_no,
                    'lot_type' => 'normal',
                    'type' => $layer_type,
                    'model' => $modelLot['models'][0] ?? null,
                    'lot_no' => $modelLot['lots'][0] ?? null,
                    'heat_treatment_completed' => !empty($layer_json),
                    'second_heat_treatment_completed' => GbdpSecondHeatTreatment::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'coating_completed' => Coating::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'second_coating_completed' => GbdpSecondCoating::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'filmpasting_completed' => FilmPastingData::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'mpi_completed' => TPMDataCategory::where('actual_model', $modelLot['models'][0] ?? null)
                        ->where('jhcurve_lotno', $modelLot['lots'][0] ?? null)
                        ->where('massprod_name', $massprod)
                        ->exists(),
                ];

                $layers[] = $layerStatus;
                return;
            }

            // Breaklot scenario
            // 1️⃣ Main lot from BreaklotInitialLotHt (primary)
            $mainLot = BreaklotInitialLotHt::where('furnace', $furnace)
                ->where('mass_prod', $massprod)
                ->where('layer', $layer_no)
                ->first();

            // 🔁 Fallback to BreaklotInitialLot if not found
            if (!$mainLot) {
                $mainLot = BreaklotInitialLot::where('furnace', $furnace)
                    ->where('mass_prod', $massprod)
                    ->where('layer', $layer_no)
                    ->first();
            }

            $secondHeatTreatmentCompleted = false;
            $secondHeatTreatmentBreaklotCompleted = false;
            if ($layer_type === '1st and 2nd Gbdp') {
                $secondHeatTreatmentCompleted = GbdpSecondHeatTreatment::where('furnace', $furnace)
                    ->where('mass_prod', $massprod)
                    ->where('layer', $layer_no)
                    ->exists();
                $secondHeatTreatmentBreaklotCompleted = BreaklotSecondHeatTreatment::where('furnace', $furnace)
                    ->where('mass_prod', $massprod)
                    ->where('layer', $layer_no)
                    ->exists();
            }

            if ($mainLot) {
                $layers[] = [
                    'layer_no' => $layer_no,
                    'lot_type' => 'main',
                    'type' => $layer_type,
                    'model' => $mainLot->initial_model,
                    'lot_no' => $mainLot->initial_lot,
                    'heat_treatment_completed' => !empty($layer_json),
                    'second_heat_treatment_completed' => $secondHeatTreatmentCompleted,
                    'coating_completed' => Coating::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'filmpasting_completed' => FilmPastingData::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'second_coating_completed' => GbdpSecondCoating::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->exists(),
                    'mpi_completed' => TPMDataCategory::where('actual_model', $mainLot->initial_model)
                        ->where('jhcurve_lotno', $mainLot->initial_lot)
                        ->where('massprod_name', $massprod)
                        ->exists(),
                ];
            }

            // 2️⃣ Additional lots
            $additionalTables = [BreaklotCoating::class, BreaklotSecondCoating::class, BreaklotFilmpasting::class];
            foreach ($additionalTables as $table) {
                $additionalLots = $table::where('furnace', $furnace)
                    ->where('mass_prod', $massprod)
                    ->where('layer', $layer_no)
                    ->get();

                foreach ($additionalLots as $lot) {

                    $additionalType = BreaklotAddtnlFormatType::where('furnace', $furnace)
                        ->where('mass_prod', $massprod)
                        ->where('layer', $layer_no)
                        ->where('model', $lot->model)
                        ->where('lot_no', $lot->lot_no)
                        ->value('format_type');

                    if (!$additionalType) {

                        // Check Film Pasting first (highest priority)
                        $hasFilmPasting = BreaklotFilmpasting::where('furnace', $furnace)
                            ->where('mass_prod', $massprod)
                            ->where('layer', $layer_no)
                            ->where('model', $lot->model)
                            ->where('lot_no', $lot->lot_no)
                            ->exists();

                        if ($hasFilmPasting) {
                            $additionalType = 'Film Pasting';
                        } elseif (stripos($lot->model, '2ND GBDP') !== false) {
                            $additionalType = '1st and 2nd Gbdp';
                        } else {
                            $additionalType = 'Normal';
                        }

                        BreaklotAddtnlFormatType::updateOrCreate(
                            [
                                'furnace' => $furnace,
                                'mass_prod' => $massprod,
                                'layer' => (string) $layer_no,
                                'model' => $lot->model,
                                'lot_no' => $lot->lot_no,
                            ],
                            [
                                'format_type' => $additionalType,
                            ]
                        );
                    }

                    $layers[] = [
                        'layer_no' => $layer_no,
                        'lot_type' => 'additional',
                        'type' => $additionalType,
                        'model' => $lot->model,
                        'lot_no' => $lot->lot_no,
                        'heat_treatment_completed' => !empty($layer_json),
                        'second_heat_treatment_completed' => $secondHeatTreatmentBreaklotCompleted,
                        'coating_completed' => BreaklotCoating::where('furnace', $furnace)
                            ->where('mass_prod', $massprod)
                            ->where('layer', $layer_no)
                            ->exists(),
                        'second_coating_completed' => BreaklotSecondCoating::where('furnace', $furnace)
                            ->where('mass_prod', $massprod)
                            ->where('layer', $layer_no)
                            ->where('model', $lot->model)
                            ->where('lot_no', $lot->lot_no)
                            ->exists(),
                        'filmpasting_completed' => BreaklotFilmpasting::where('furnace', $furnace)
                            ->where('mass_prod', $massprod)
                            ->where('layer', $layer_no)
                            ->where('model', $lot->model)
                            ->where('lot_no', $lot->lot_no)
                            ->exists(),
                        'mpi_completed' => TPMDataCategory::where('actual_model', $lot->model)
                            ->where('jhcurve_lotno', $lot->lot_no)
                            ->where('massprod_name', $massprod)
                            ->exists(),
                    ];
                }
            }
        };

        // Layers 1–9
        for ($i = 1; $i <= 9; $i++) {
            $layer_json = $prod->{'layer_' . $i};
            $layer_type = $prod->{'layer_' . $i . '_format_type'};
            $processLayer($layer_json, $i, $layer_type);
        }

        // Layer 9.5
        $layer_9_5_json = $prod->layer_9_5;
        $layer_9_5_type = $prod->layer_9_5_format_type;
        $processLayer($layer_9_5_json, '9.5', $layer_9_5_type);

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
        $boxLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'];

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
                /// Entire layer empty → all boxes are available
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
        $allBoxes = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K'];

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
        $month = $request->input('month');
        $year  = $request->input('year');

        $records = MassProduction::whereMonth('estimated_completion', $month)
            ->whereYear('estimated_completion', $year)
            ->get();

        if ($records->isEmpty()) {
            return response()->json([]);
        }

        $layerKeys = [
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

        /**
         * Final accumulator:
         * [
         *   "MODEL|LOT" => [
         *      base fields...
         *      qty
         *      wt
         *      layers[]
         *   ]
         * ]
         */
        $summary = [];

        foreach ($records as $record) {

            $baseRow = [
                'MACHINE NO'  => $record->machine_no,
                'CYCLE NO'    => $record->cycle_no,
                'PTN'         => $record->pattern_no,
                'DATE LOAD'   => $record->date_start,
                'DATE UNLOAD' => $record->date_finished,
            ];

            /**
             * ----------------------------
             * MASS PRODUCTION LAYERS
             * ----------------------------
             */
            foreach ($layerKeys as $layerKey => $layerLabel) {

                if (empty($record->$layerKey)) {
                    continue;
                }

                $rows = collect($this->decodeJson($record->$layerKey))->keyBy('rowTitle');

                $models = $rows['MODEL:']['data'] ?? [];
                $lots   = $rows['LT. No.:']['data'] ?? [];
                $qtys   = $rows['QTY (PCS):']['data'] ?? [];
                $wts    = $rows['WT (KG):']['data'] ?? [];

                foreach ($models as $letter => $model) {
                    $lot = $lots[$letter] ?? null;
                    if (!$model || !$lot) {
                        continue;
                    }

                    $key = "{$model}|{$lot}";

                    if (!isset($summary[$key])) {
                        $summary[$key] = array_merge($baseRow, [
                            'MODEL NAME'      => $model,
                            'LOT'             => $lot,
                            'QTY'             => 0,
                            'BEFORE GBDP WT'  => 0,
                            'LAYERS'          => [],
                        ]);
                    }

                    $summary[$key]['QTY'] += (int) ($qtys[$letter] ?? 0);
                    $summary[$key]['BEFORE GBDP WT'] += (float) ($wts[$letter] ?? 0);
                    $summary[$key]['LAYERS'][$layerLabel] = true;
                }
            }

            /**
             * ----------------------------
             * EXCESS LAYERS (NO LAYER TAG)
             * ----------------------------
             */
            $excessLayers = ExcessLayers::where('mass_prod', $record->mass_prod)
                ->where('furnace', $record->furnace)
                ->get();

            foreach ($excessLayers as $excess) {

                $rows = collect($this->decodeJson($excess->layer_data))->keyBy('rowTitle');

                $models = $rows['MODEL:']['data'] ?? [];
                $lots   = $rows['LT. No.:']['data'] ?? [];
                $qtys   = $rows['QTY (PCS):']['data'] ?? [];
                $wts    = $rows['WT (KG):']['data'] ?? [];

                foreach ($models as $letter => $model) {
                    $lot = $lots[$letter] ?? null;
                    if (!$model || !$lot) {
                        continue;
                    }

                    $key = "{$model}|{$lot}";

                    if (!isset($summary[$key])) {
                        $summary[$key] = array_merge($baseRow, [
                            'MODEL NAME'      => $model,
                            'LOT'             => $lot,
                            'QTY'             => 0,
                            'BEFORE GBDP WT'  => 0,
                            'LAYERS'          => [],
                        ]);
                    }

                    $summary[$key]['QTY'] += (int) ($qtys[$letter] ?? 0);
                    $summary[$key]['BEFORE GBDP WT'] += (float) ($wts[$letter] ?? 0);
                }
            }
        }

        /**
         * ----------------------------
         * FINAL CALCULATIONS (UNCHANGED)
         * ----------------------------
         */
        $excelData = [];

        foreach ($summary as $row) {

            $htData = HtMassProData::where('model_name', $row['MODEL NAME'])
                ->latest('id')
                ->first();

            $productWeight = $htData?->product_weight ?? 0;
            $secondSl      = $htData?->{'2nd_sl'} ?? 0;
            $htQty         = $htData?->qty ?? 0;

            $afterGbdpWt = ($row['QTY'] * $productWeight * $secondSl) / 1_000_000;
            $equivalentLots = $row['QTY'] * $htQty;

            $excelData[] = array_merge($row, [
                'AFTER GBDP WT'   => $afterGbdpWt,
                'EQUIVALENT LOTS' => $equivalentLots,
                'LAYER'          => implode(', ', array_keys($row['LAYERS'])),
            ]);
        }

        return $excelData;
    }

    private function decodeJson($value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (is_string($value) && $value !== '') {
            return json_decode($value, true) ?? [];
        }

        return [];
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

        // Determine mass production column
        $column = $layer === '9.5' ? 'layer_9_5' : 'layer_' . $layer;

        // --- 1. Fetch the mass production row ---
        $mp = MassProduction::where('mass_prod', $massProd)
            ->where('furnace', $furnace)
            ->first();

        if (!$mp) {
            Log::error('Mass production not found', ['massProd' => $massProd, 'furnace' => $furnace]);
            return response()->json(['success' => false, 'message' => 'Mass Production not found'], 404);
        }

        // --- 2. Decode layer JSON ---
        $layerData = json_decode($mp->$column, true);

        // --- 2a. Gather all unique MODEL + LT pairs ---
        $uniquePairs = [];
        if (!empty($layerData)) {
            $modelRow = null;
            $lotRow   = null;

            foreach ($layerData as $row) {
                if ($row['rowTitle'] === 'MODEL:') $modelRow = $row['data'] ?? [];
                if ($row['rowTitle'] === 'LT. No.:') $lotRow = $row['data'] ?? [];
            }

            if ($modelRow && $lotRow) {
                foreach ($modelRow as $box => $modelValue) {
                    $lotValue = $lotRow[$box] ?? null;
                    if ($modelValue && $lotValue) {
                        $uniquePairs["{$modelValue}|{$lotValue}"] = true; // using associative keys to deduplicate
                    }
                }
            }
        }

        // --- 3. Null the mass production layer ---
        $mp->$column = null;
        $mp->save();

        // --- 4. Delete matching excess layer rows ---
        $deletedRows = 0;

        if (!empty($uniquePairs)) {
            $excessLayers = ExcessLayers::where('mass_prod', $massProd)
                ->where('furnace', $furnace)
                ->get();

            foreach ($excessLayers as $excess) {
                $layerDataExcess = is_array($excess->layer_data) ? $excess->layer_data : json_decode($excess->layer_data, true);

                $shouldDelete = false;

                if (!empty($layerDataExcess)) {
                    $excessModelRow = null;
                    $excessLotRow   = null;

                    foreach ($layerDataExcess as $row) {
                        if ($row['rowTitle'] === 'MODEL:') $excessModelRow = $row['data'] ?? [];
                        if ($row['rowTitle'] === 'LT. No.:') $excessLotRow = $row['data'] ?? [];
                    }

                    if ($excessModelRow && $excessLotRow) {
                        foreach ($excessModelRow as $box => $modelValue) {
                            $lotValue = $excessLotRow[$box] ?? null;
                            if (isset($uniquePairs["{$modelValue}|{$lotValue}"])) {
                                $shouldDelete = true;
                                break; // Found a matching pair, delete this excess layer
                            }
                        }
                    }
                }

                if ($shouldDelete) {
                    $excess->delete();
                    $deletedRows++;
                }
            }
        }

        // --- 5. Delete matching BreaklotInitialLotHt rows ---
        $deletedBreaklotRows = BreaklotInitialLotHt::where('mass_prod', $massProd)
            ->where('furnace', $furnace)
            ->where('layer', $layer)
            ->delete();

        $deletedBreaklotAddtnlRows = BreaklotAddtnlFormatType::where('mass_prod', $massProd)
            ->where('furnace', $furnace)
            ->where('layer', (string) $layer)
            ->delete();

        $totalDeleted = $deletedRows + $deletedBreaklotRows + $deletedBreaklotAddtnlRows;

        return response()->json([
            'success' => true,
            'message' => 'Layer deleted successfully',
            'deleted_rows' => $deletedRows,
            'deleted_breaklot_rows' => $deletedBreaklotRows,
            'deleted_breaklot_addtnl_rows' => $deletedBreaklotAddtnlRows,
            'total_deleted' => $totalDeleted,
        ]);
    }

    public function deleteExistingData(Request $request)
    {
        $massProd = $request->massprod;
        $furnace  = $request->furnace;
        $layer    = $request->layer;

        // List of models to clean
        $tables = [
            Coating::class,
            GbdpSecondCoating::class,
            GbdpSecondHeatTreatment::class,
            FilmPastingData::class,
            BreaklotInitialLot::class,
            BreaklotCoating::class,
            BreaklotSecondCoating::class,
            BreaklotFilmpasting::class,
            BreaklotSecondHeatTreatment::class,
        ];

        foreach ($tables as $model) {
            $model::where('mass_prod', $massProd)
                ->where('furnace', $furnace)
                ->where('layer', $layer)
                ->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Existing data deleted successfully'
        ]);
    }

    public function deleteLayerFull(Request $request)
    {
        $validated = $request->validate([
            'massprod' => 'required|string',
            'furnace'  => 'required|string',
            'layer'    => 'required|string',
        ]);

        $massProd = $validated['massprod'];
        $furnace  = $validated['furnace'];
        $layer    = $validated['layer'];

        try {

            return DB::transaction(function () use ($massProd, $furnace, $layer) {

                // Normalize layer column safely
                $normalizedLayer = str_replace('.', '_', trim($layer));
                $column = 'layer_' . $normalizedLayer;

                $mp = MassProduction::where('mass_prod', $massProd)
                    ->where('furnace', $furnace)
                    ->first();

                if (!$mp) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mass Production not found'
                    ], 404);
                }

                // Extract MODEL / LOT pairs
                $layerData = json_decode($mp->$column, true);
                $uniquePairs = [];

                if (!empty($layerData)) {
                    $modelRow = null;
                    $lotRow   = null;

                    foreach ($layerData as $row) {
                        if (($row['rowTitle'] ?? null) === 'MODEL:') {
                            $modelRow = $row['data'] ?? [];
                        }

                        if (($row['rowTitle'] ?? null) === 'LT. No.:') {
                            $lotRow = $row['data'] ?? [];
                        }
                    }

                    if ($modelRow && $lotRow) {
                        foreach ($modelRow as $box => $modelValue) {
                            $lotValue = $lotRow[$box] ?? null;

                            if ($modelValue && $lotValue) {
                                $uniquePairs["{$modelValue}|{$lotValue}"] = true;
                            }
                        }
                    }
                }

                // Null the layer
                $mp->$column = null;
                $mp->save();

                $deletedExcess = 0;

                // Delete Excess Layers
                if (!empty($uniquePairs)) {

                    $excessLayers = ExcessLayers::where('mass_prod', $massProd)
                        ->where('furnace', $furnace)
                        ->get();

                    foreach ($excessLayers as $excess) {

                        $layerDataExcess = is_array($excess->layer_data)
                            ? $excess->layer_data
                            : json_decode($excess->layer_data, true);

                        $shouldDelete = false;

                        if (!empty($layerDataExcess)) {

                            $exModel = null;
                            $exLot   = null;

                            foreach ($layerDataExcess as $row) {
                                if (($row['rowTitle'] ?? null) === 'MODEL:') {
                                    $exModel = $row['data'] ?? [];
                                }

                                if (($row['rowTitle'] ?? null) === 'LT. No.:') {
                                    $exLot = $row['data'] ?? [];
                                }
                            }

                            if ($exModel && $exLot) {
                                foreach ($exModel as $box => $modelValue) {
                                    $lotValue = $exLot[$box] ?? null;

                                    if (isset($uniquePairs["{$modelValue}|{$lotValue}"])) {
                                        $shouldDelete = true;
                                        break;
                                    }
                                }
                            }
                        }

                        if ($shouldDelete) {
                            $excess->delete();
                            $deletedExcess++;
                        }
                    }
                }

                // Breaklot deletes
                $deletedBreaklotInitial = BreaklotInitialLotHt::where('mass_prod', $massProd)
                    ->where('furnace', $furnace)
                    ->where('layer', $layer)
                    ->delete();

                $deletedBreaklotAddtnl = BreaklotAddtnlFormatType::where('mass_prod', $massProd)
                    ->where('furnace', $furnace)
                    ->where('layer', (string) $layer)
                    ->delete();

                // Existing table cleanup
                $tables = [
                    Coating::class,
                    GbdpSecondCoating::class,
                    GbdpSecondHeatTreatment::class,
                    FilmPastingData::class,
                    BreaklotInitialLot::class,
                    BreaklotCoating::class,
                    BreaklotSecondCoating::class,
                    BreaklotFilmpasting::class,
                    BreaklotSecondHeatTreatment::class,
                ];

                $deletedExisting = 0;

                foreach ($tables as $model) {
                    $deletedExisting += $model::where('mass_prod', $massProd)
                        ->where('furnace', $furnace)
                        ->where('layer', $layer)
                        ->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Layer fully deleted successfully',
                    'deleted_excess_layers' => $deletedExcess,
                    'deleted_breaklot_initial' => $deletedBreaklotInitial,
                    'deleted_breaklot_addtnl' => $deletedBreaklotAddtnl,
                    'deleted_existing_rows' => $deletedExisting,
                    'total_deleted' =>
                        $deletedExcess +
                        $deletedBreaklotInitial +
                        $deletedBreaklotAddtnl +
                        $deletedExisting,
                ]);
            });

        } catch (\Throwable $e) {

            Log::error('Layer full deletion failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'massProd' => $massProd ?? null,
                'furnace' => $furnace ?? null,
                'layer' => $layer ?? null,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Deletion failed'
            ], 500);
        }
    }

    public function getAssociatedModel(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
            'layer'     => 'required|string',
            'lot_no'    => 'required|string',
        ]);

        $mass_prod = trim($validated['mass_prod']);
        $furnace   = trim($validated['furnace']);
        $layer     = trim($validated['layer']);
        $lot_no    = trim($validated['lot_no']);

        $massProduction = MassProduction::where('mass_prod', $mass_prod)
            ->where('furnace', $furnace)
            ->first();

        if (!$massProduction) {
            return response()->json(['message' => 'Mass production not found'], 404);
        }

        // Map layer number to column
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

        if (!isset($layerMap[$layer])) {
            return response()->json(['message' => 'Invalid layer'], 400);
        }

        $layerColumn = $layerMap[$layer];

        if (empty($massProduction->$layerColumn)) {
            return response()->json(['message' => 'Layer data is empty'], 404);
        }

        $layerData = json_decode($massProduction->$layerColumn, true);

        if (!is_array($layerData)) {
            return response()->json(['message' => 'Invalid layer JSON'], 500);
        }

        // Find LT. No. row and MODEL row
        $ltRow = null;
        $modelRow = null;

        foreach ($layerData as $row) {
            if (($row['rowTitle'] ?? '') === 'LT. No.:') {
                $ltRow = $row;
            }
            if (($row['rowTitle'] ?? '') === 'MODEL:') {
                $modelRow = $row;
            }
        }

        if (!$ltRow || !$modelRow) {
            return response()->json(['message' => 'Required rows not found in layer data'], 404);
        }

        // Find which letter (A, B, C, etc) matches the lot number
        $foundKey = null;

        foreach (($ltRow['data'] ?? []) as $key => $value) {
            if ((string)$value === (string)$lot_no) {
                $foundKey = $key;
                break;
            }
        }

        if (!$foundKey) {
            return response()->json(['message' => 'Lot number not found in this layer'], 404);
        }

        // Get model using same letter key
        $model = $modelRow['data'][$foundKey] ?? null;

        if (!$model) {
            return response()->json(['message' => 'Model not found for matched lot key'], 404);
        }

        return response()->json([
            'lot_no' => $lot_no,
            'key'    => $foundKey,
            'model'  => $model,
        ]);
    }

    public function getAllLotStatusPreview(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
        ]);

        $mass_prod = trim($validated['mass_prod']);
        $furnace   = trim($validated['furnace']);

        $massProduction = MassProduction::where('mass_prod', $mass_prod)
            ->where('furnace', $furnace)
            ->first();

        if (!$massProduction) {
            return response()->json(['message' => 'Mass production not found'], 404);
        }

        $layers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '9.5'];

        $finalLots = [];
        $assignedBoxes = [];
        $mainBoxLayerMap = [];

        foreach ($layers as $layer) {
            $column = $layer === '9.5' ? 'layer_9_5' : 'layer_' . $layer;

            $massProdRaw  = $massProduction->{$column} ?? null;
            $massProdData = is_array($massProdRaw)
                ? $massProdRaw
                : json_decode($massProdRaw, true);

            $boxes = !empty($massProdData)
                ? $this->extractBoxes($massProdData)
                : [];

            // -------------------------
            // Only add ExcessBoxes for QTY/WT, not layers
            // -------------------------
            $excessBoxes = [];
            $excess = ExcessLayers::where([
                'mass_prod' => $mass_prod,
                'furnace'   => $furnace,
                'layer'     => $layer,
            ])->first();

            if ($excess && !empty($excess->layer_data)) {
                $excessData = is_array($excess->layer_data)
                    ? $excess->layer_data
                    : json_decode($excess->layer_data, true);

                if (!empty($excessData)) {
                    $excessBoxes = $this->extractBoxes($excessData);
                }
            }

            // Merge boxes only for QTY/WT, layers come from massProduction only
            $allBoxes = array_merge($boxes, $excessBoxes);

            foreach ($allBoxes as $box => $data) {
                if (empty($data['model']) || empty($data['lt_no'])) continue;

                $lotKey = $data['model'] . '|' . $data['lt_no'];

                if (!isset($finalLots[$lotKey])) {
                    $finalLots[$lotKey] = [
                        'model'        => $data['model'],
                        'lt_no'        => $data['lt_no'],
                        'total_qty'    => 0,
                        'total_wt'     => 0,
                        'layers'       => [], // only from MassProduction
                        'main_boxes'   => [],
                        'excess_boxes' => [],
                    ];
                    $assignedBoxes[$lotKey] = [];
                    $mainBoxLayerMap[$lotKey] = [];
                }

                $finalLots[$lotKey]['total_qty'] += (int) ($data['qty'] ?? 0);
                $finalLots[$lotKey]['total_wt']  += (float) ($data['wt'] ?? 0);

                if (!in_array($box, $assignedBoxes[$lotKey])) {
                    $finalLots[$lotKey]['main_boxes'][$box] = true;
                    $assignedBoxes[$lotKey][] = $box;

                    // ONLY record layers from MassProduction table
                    if (isset($boxes[$box])) {
                        $mainBoxLayerMap[$lotKey][$box] = $layer;
                    }
                } else {
                    $finalLots[$lotKey]['excess_boxes'][$box] = true;
                }
            }
        }

        // Finalize layers per lot
        foreach ($finalLots as $lotKey => &$lot) {
            if (!empty($mainBoxLayerMap[$lotKey])) {
                $layersUsed = array_unique(array_values($mainBoxLayerMap[$lotKey]));
                sort($layersUsed);
                $lot['layers'] = $layersUsed;
            }
        }

        // Normalize output for Vue
        $response = collect($finalLots)->map(function ($lot) {
            return [
                'model'        => $lot['model'],
                'lt_no'        => $lot['lt_no'],
                'total_qty'    => $lot['total_qty'],
                'total_wt'     => $lot['total_wt'],
                'layers'       => $lot['layers'], // ONLY MassProduction layers
                'main_boxes'   => array_keys($lot['main_boxes']),
                'excess_boxes' => array_keys($lot['excess_boxes']),
            ];
        })->values();

        return response()->json($response);
    }



    /**
     * Extracts boxes from JSON layer data
     */
    private function extractBoxes(array $rows): array
    {
        $boxes = [];
        $map = [
            'MODEL:'     => 'model',
            'LT. No.:'   => 'lt_no',
            'QTY (PCS):' => 'qty',
            'WT (KG):'   => 'wt',
        ];

        foreach ($rows as $row) {
            if (!isset($map[$row['rowTitle']])) continue;

            $field = $map[$row['rowTitle']];
            foreach ($row['data'] as $box => $value) {
                $boxes[$box][$field] = $value;
            }
        }

        return $boxes;
    }

    private function extractLotRows(array $layerData, string $layer)
    {
        $map = [];

        $modelRow = collect($layerData)->firstWhere('rowTitle', 'MODEL:');
        $lotRow   = collect($layerData)->firstWhere('rowTitle', 'LT. No.:');
        $qtyRow   = collect($layerData)->firstWhere('rowTitle', 'QTY (PCS):');
        $wtRow    = collect($layerData)->firstWhere('rowTitle', 'WT (KG):');
        $boxRow   = collect($layerData)->firstWhere('rowTitle', 'BOX No.:');

        if (!$modelRow || !$lotRow) return [];

        foreach ($modelRow['data'] as $letter => $model) {
            $lot = $lotRow['data'][$letter] ?? null;
            if (!$model || !$lot) continue;

            $key = "{$model}|{$lot}";

            $map[$key] = [
                'model'      => $model,
                'lt_no'      => $lot,
                'total_qty'  => (float) ($qtyRow['data'][$letter] ?? 0),
                'total_wt'   => (float) ($wtRow['data'][$letter] ?? 0),
                'layers'     => [$layer],
                'boxes'      => array_filter([$boxRow['data'][$letter] ?? null]),
            ];
        }

        return $map;
    }

    public function mpiReportStatusPanel(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
        ]);

        $massProd = trim($validated['mass_prod']);
        $furnace  = trim($validated['furnace']);

        $massProduction = MassProduction::where('mass_prod', $massProd)
            ->where('furnace', $furnace)
            ->first();

        if (!$massProduction) {
            return response()->json(['message' => 'Mass production not found'], 404);
        }

        $expectedLayers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '9.5'];
        $lotsByLayer = [];

        foreach ($expectedLayers as $layer) {
            $column = $layer === '9.5' ? 'layer_9_5' : 'layer_' . $layer;
            $layerDataRaw = $massProduction->{$column} ?? null;
            $layerData = is_array($layerDataRaw) ? $layerDataRaw : json_decode($layerDataRaw, true);

            if (empty($layerData)) {
                continue;
            }

            $boxes = $this->extractBoxes($layerData);

            $uniqueLots = [];
            foreach ($boxes as $box => $data) {
                if (empty($data['model']) || empty($data['lt_no'])) continue;

                $lotKey = $data['model'] . '|' . $data['lt_no'];
                if (!isset($uniqueLots[$lotKey])) {
                    $uniqueLots[$lotKey] = [
                        'layer' => $layer,
                        'model' => $data['model'],
                        'lt_no' => $data['lt_no'],
                        'status' => 'yellow',
                        'serial' => null,
                    ];
                }
            }

            // Assign serials, handle single bigint columns
            foreach ($uniqueLots as &$lot) {
                $candidates = TPMDataCategory::where('actual_model', $lot['model'])
                    ->where('jhcurve_lotno', $lot['lt_no'])
                    ->get();

                if ($candidates->isEmpty()) continue;

                $foundMatch = false;

                $serialColumn = $lot['layer'] === '9.5'
                    ? 'layer_9_5_serial'
                    : 'layer_' . $lot['layer'] . '_serial';

                $serialValue = $massProduction->{$serialColumn} ?? null;

                foreach ($candidates as $candidate) {
                    if ($candidate->tpm_data_serial == $serialValue) {
                        $lot['serial'] = $serialValue;
                        $lot['status'] = 'green';
                        $foundMatch = true;
                        break;
                    }
                }

                // fallback if no match found
                if (!$foundMatch) {
                    $first = $candidates->first();
                    $lot['serial'] = $first->tpm_data_serial ?? null;
                    $lot['status'] = 'green';
                }
            }
            unset($lot);

            $lotsByLayer[$layer] = array_values($uniqueLots);
        }

        return response()->json(['layers' => $lotsByLayer]);
    }

    public function checkEncodedData(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
            'layer'     => 'required|string',
        ]);

        $massProd = $validated['mass_prod'];
        $furnace  = $validated['furnace'];
        $layer    = $validated['layer'];

        $tables = [
            'coating' => Coating::class,
            'gbdp_second_coating' => GbdpSecondCoating::class,
            'gbdp_second_heat_treatment' => GbdpSecondHeatTreatment::class,
            'film_pasting' => FilmPastingData::class,
            'breaklot_initial_lot' => BreaklotInitialLot::class,
            'breaklot_coating' => BreaklotCoating::class,
            'breaklot_second_coating' => BreaklotSecondCoating::class,
            'breaklot_filmpasting' => BreaklotFilmpasting::class,
        ];

        foreach ($tables as $key => $model) {
            if ($model::where('mass_prod', $massProd)
                ->where('furnace', $furnace)
                ->where('layer', $layer)
                ->exists()
            ) {
                return response()->json([
                    'exists' => true,
                    'source' => $key,
                ]);
            }
        }

        return response()->json([
            'exists' => false,
            'source' => null,
        ]);
    }

    public function checkControlSheetLayers(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
        ]);

        $production = MassProduction::where('furnace', $request->furnace)
            ->where('mass_prod', $request->mass_prod)
            ->first();

        if (!$production) {
            return response()->json([
                'message' => 'Mass Production not found.',
                'is_incomplete' => true,
                'missing_layers' => [],
            ], 404);
        }

        $layers = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '9.5'];

        $missingLayers = [];

        foreach ($layers as $layerRaw) {

            $layerColumn = $layerRaw === '9.5'
                ? 'layer_9_5'
                : 'layer_' . $layerRaw;

            $layerData = $production->$layerColumn ?? null;

            if (empty($layerData)) {

                $existsInExcess = ExcessLayers::where('furnace', $request->furnace)
                    ->where('mass_prod', $request->mass_prod)
                    ->where('layer', $layerRaw)
                    ->exists();

                if (!$existsInExcess) {
                    $missingLayers[] = $layerRaw;
                }
            }
        }

        return response()->json([
            'is_incomplete' => count($missingLayers) > 0,
            'missing_layers' => $missingLayers,
        ]);
    }

    public function checkValidateMassProduction(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string',
            'furnace'   => 'required|string',
            'layer'     => 'nullable|string',
        ]);

        $massProd = $validated['mass_prod'];
        $furnace  = $validated['furnace'];
        $layer    = $validated['layer'] ?? null;

        $massProdData = MassProduction::where('mass_prod', $massProd)
            ->where('furnace', $furnace)
            ->first();

        $layers = [];

        if ($massProdData) {
            foreach ($massProdData->getAttributes() as $column => $value) {

                // STRICT match: layer number only (no suffix like .serial or .format.type)
                if (preg_match('/^layer_\d+(_\d+)?$/', $column) && !empty($value)) {

                    $raw = str_replace('layer_', '', $column);
                    $normalized = str_replace('_', '.', $raw);

                    $layers[] = $normalized;
                }
            }

            $layers = array_values(array_unique($layers));
            sort($layers, SORT_NATURAL);
        }

        $massProdExists = false;

        if ($massProdData && $layer) {
            $normalizedLayer = str_replace('.', '_', $layer);
            $layerColumn = 'layer_' . $normalizedLayer;

            $massProdExists = !empty($massProdData->$layerColumn);
        } else {
            $massProdExists = (bool) $massProdData;
        }

        return response()->json([
            'mass_prod_exists' => $massProdExists,
            'available_layers' => $layers,
        ]);
    }

}
