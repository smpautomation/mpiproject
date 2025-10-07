<?php

namespace App\Http\Controllers;

use App\Models\MassProduction;
use App\Models\GbdpSecondCoating;
use App\Models\GbdpSecondHeatTreatment;
use App\Models\FilmPastingData;
use App\Models\Coating;
use App\Models\TPMData;
use App\Models\TPMDataAggregateFunctions;
use App\Models\ReportData;
use App\Models\SmpData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class MassProductionController extends Controller
{
    public function index()
    {
        return MassProduction::orderBy('created_at', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mass_prod' => 'required|string|unique:mass_productions,mass_prod',
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

    public function update(Request $request, $massProd)
    {
        // Find the record by mass_prod column
        $production = MassProduction::where('mass_prod', $massProd)->firstOrFail();

        // Validate only fields that may be sent in the payload
        $validated = $request->validate([
            'mass_prod' => 'required|string|unique:mass_productions,mass_prod,' . $production->id,
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

    public function getByMassProd($massprod)
    {
        $record = MassProduction::where('mass_prod', $massprod)->first();
        if(!$record){
            return response()->json([
                'message' => "Mass Production record not found.",
            ], 404);
        }

        return response()->json($record);
    }

    public function updateByMassProd(Request $request, $massprod)
    {
        // Find the record by mass_prod
        $production = MassProduction::where('mass_prod', $massprod)->first();

        if (!$production) {
            return response()->json([
                'message' => "Mass Production record not found.",
            ], 404);
        }

        // Validate incoming data
        $validated = $request->validate([
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

        // Update the record
        $production->update($validated);

        return response()->json([
            'message' => 'Mass Production updated successfully.',
            'data' => $production,
        ]);
    }

    public function uploadGraphs(Request $request, $massprod)
    {
        $baseDir = public_path("htgraphs/{$massprod}");
        $folders = ['cycle', 'actual', 'standard'];

        // Ensure folders exist
        foreach ($folders as $folder) {
            $path = "{$baseDir}/{$folder}";
            if (!file_exists($path)) mkdir($path, 0777, true);
        }

        // Handle cycle and actual graphs
        foreach (['cycle_graph' => 'cycle', 'actual_graph' => 'actual'] as $inputName => $folder) {
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                if ($file->isValid()) {
                    $file->move("{$baseDir}/{$folder}", 'graph.png');
                }
            }
        }

        // Handle standard graph from pattern_no
        if ($request->has('pattern_no')) {
            $patternNo = $request->input('pattern_no');
            $pattern = \App\Models\HtGraphPatterns::where('pattern_no', $patternNo)->first();

            if ($pattern) {
                $sourceDir = public_path("htgraph_patterns");
                $files = glob("{$sourceDir}/pattern_{$patternNo}.{png,jpg,jpeg}", GLOB_BRACE);

                if (count($files)) {
                    copy($files[0], "{$baseDir}/standard/graph.png"); // copy to standard folder
                }
            }
        }

        return response()->json(['message' => 'Graphs uploaded successfully.']);
    }

    public function getLayerModel($massprod, $layerNumber)
    {
        $record = MassProduction::where('mass_prod', $massprod)->first();

        if (!$record) {
            return response()->json([
                'message' => "Mass Production record not found.",
            ], 404);
        }

        // Normalize layer number (e.g., 9.5 → layer_9_5)
        $layerColumn = 'layer_' . str_replace('.', '_', $layerNumber);

        if (!isset($record->$layerColumn)) {
            return response()->json([
                'message' => "Layer {$layerNumber} not found for this Mass Production.",
            ], 404);
        }

        $layerData = json_decode($record->$layerColumn, true);

        if (empty($layerData) || !is_array($layerData)) {
            return response()->json([
                'message' => "No valid data in Layer {$layerNumber}.",
            ], 404);
        }

        // Search for row with "MODEL:"
        foreach ($layerData as $row) {
            if (isset($row['rowTitle']) && $row['rowTitle'] === 'MODEL:') {
                return response()->json([
                    'model' => $row['data']['A'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => "MODEL row not found in Layer {$layerNumber}.",
        ], 404);
    }

    public function getLayerLotno($massprod, $layerNumber)
    {
        $record = MassProduction::where('mass_prod', $massprod)->first();

        if (!$record) {
            return response()->json([
                'message' => "Mass Production record not found.",
            ], 404);
        }

        // Normalize layer number (e.g., 9.5 → layer_9_5)
        $layerColumn = 'layer_' . str_replace('.', '_', $layerNumber);

        if (!isset($record->$layerColumn)) {
            return response()->json([
                'message' => "Layer {$layerNumber} not found for this Mass Production.",
            ], 404);
        }

        $layerData = json_decode($record->$layerColumn, true);

        if (empty($layerData) || !is_array($layerData)) {
            return response()->json([
                'message' => "No valid data in Layer {$layerNumber}.",
            ], 404);
        }

        // Search for row with "MODEL:"
        foreach ($layerData as $row) {
            if (isset($row['rowTitle']) && $row['rowTitle'] === 'LT. No.:') {
                return response()->json([
                    'lotno' => $row['data']['A'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => "LOTNO row not found in Layer {$layerNumber}.",
        ], 404);
    }

    public function getAllHTCompletedLayers($massprod)
    {
        // Fetch MassProduction record
        $record = MassProduction::where('mass_prod', $massprod)->first();
        if (!$record) {
            return response()->json([
                'message' => "Mass Production record not found.",
            ], 404);
        }

        // Define all possible layer columns
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

        // Check MassProduction layers
        foreach ($layers as $column => $label) {
            $value = $record->$column;
            if (!empty($value) && $value !== 'null') {
                $decoded = json_decode($value, true);
                if (!empty($decoded)) {
                    $completed[] = $label;
                }
            }
        }

        // Fetch GbdpSecondHeatTreatment layers for this mass production
        $gbdpLayers = GbdpSecondHeatTreatment::where('mass_prod', $massprod)
                        ->pluck('layer')
                        ->toArray();

        // Merge, remove duplicates, and sort numerically
        $allCompletedLayers = array_unique(array_merge($completed, $gbdpLayers));
        sort($allCompletedLayers, SORT_NUMERIC); // numeric ascending

        return response()->json([
            'completed_layers' => $allCompletedLayers,
        ]);
    }


    public function getAllCoatingCompleteLayers($massprod)
    {
        // Get layers from gbdp_second_coatings
        $secondLayers = GbdpSecondCoating::where('mass_prod', $massprod)
            ->whereNotNull('layer')
            ->pluck('layer')
            ->map(fn($layer) => (string) $layer);

        // Get layers from coatings
        $coatingLayers = Coating::where('mass_prod', $massprod)
            ->whereNotNull('layer')
            ->pluck('layer')
            ->map(fn($layer) => (string) $layer);

        // Get layers from film_pasting_data
        $filmLayers = FilmPastingData::where('mass_prod', $massprod)
            ->whereNotNull('layer')
            ->pluck('layer')
            ->map(fn($layer) => (string) $layer);

        // Merge, remove duplicates, sort, reindex
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

    public function getAllSecondHTCompletedLayers($massprod)
    {
        // Fetch layers from gbdp_second_heat_treatments table only
        $gbdpLayers = GbdpSecondHeatTreatment::where('mass_prod', $massprod)
            ->pluck('layer')
            ->filter() // remove nulls
            ->map(fn($layer) => (string)$layer) // cast to string
            ->toArray();

        return response()->json([
            '2nd_gbdp_layers' => $gbdpLayers,
        ]);
    }

    public function getAllFilmPastingCompletedLayers($massprod)
    {
        // Fetch layers from gbdp_second_heat_treatments table only
        $filmPastingLayers = FilmPastingData::where('mass_prod', $massprod)
            ->pluck('layer')
            ->filter() // remove nulls
            ->map(fn($layer) => (string)$layer) // cast to string
            ->toArray();

        return response()->json([
            'film_pasting_layers' => $filmPastingLayers,
        ]);
    }

    public function getLayerDataBySerial($massprod, $serial)
    {

        $production = MassProduction::where('mass_prod', $massprod)->firstOrFail();

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
            'message' => "No layer data found for serial: {$serial}"
        ], 404);
    }

    public function getLayerDataByLayerNo($massprod, $layer)
    {
        $production = MassProduction::where('mass_prod', $massprod)->firstOrFail();

        // Map numeric layer identifiers to actual DB columns
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

        // Check if provided layer number exists in the map
        if (!array_key_exists($layer, $layerMap)) {
            return response()->json([
                'message' => "Invalid layer number: {$layer}"
            ], 400);
        }

        $layerColumn = $layerMap[$layer];
        $layerData = $production->$layerColumn ? json_decode($production->$layerColumn, true) : null;

        if (!$layerData) {
            return response()->json([
                'message' => "No data found for layer: {$layer}"
            ], 404);
        }

        return response()->json([
            'layer_column' => $layerColumn,
            'layer_data'   => $layerData,
        ]);
    }

    public function smpDataSummary($massprod){
        $massProdData = MassProduction::where('mass_prod', $massprod)->first();

        if (!$massProdData) {
            return response()->json(['error' => 'Mass production not found'], 404);
        }

        $attributes = collect($massProdData->getAttributes());

        // -----------------------------
        // Extract main layers and serials
        // -----------------------------
        $layersData = $attributes
            ->filter(fn($value, $key) => preg_match('/^layer_\d+(_\d+)?$/', $key) && $value !== null)
            ->mapWithKeys(fn($value, $key) => [$key => @json_decode($value, true) ?: $value]);

        $layerSerials = $attributes
            ->filter(fn($value, $key) => preg_match('/^layer_\d+(_\d+)?_serial$/', $key) && $value !== null);

        // -----------------------------
        // Fetch all related data at once
        // -----------------------------
        $tpmRecords = TPMData::whereIn('serial_no', $layerSerials)->get()->groupBy('serial_no');
        $tpmAggregates = TPMDataAggregateFunctions::whereIn('tpm_data_serial', $layerSerials)->get()->keyBy('tpm_data_serial');
        $reportRecords = ReportData::whereIn('tpm_data_serial', $layerSerials)->get()->keyBy('tpm_data_serial');
        $coatings = Coating::where('mass_prod', $massprod)->get()->keyBy(fn($c) => $c->layer);



        // -----------------------------
        // Build flat data per layer for frontend
        // -----------------------------
        $flattenedLayers = [];

        foreach ($layersData as $layerCol => $layerArray) {
            $serial = $massProdData->{$layerCol . '_serial'} ?? null;
            $formatType = $massProdData->{$layerCol . '_format_type'} ?? null;

            $coating = null;
            $layerNumber = (float) str_replace('_', '.', str_replace('layer_', '', $layerCol));
            if (isset($coatings[$layerNumber])) {
                $coating = $coatings[$layerNumber];
            }

            $tpmData = $serial && isset($tpmRecords[$serial]) ? $tpmRecords[$serial]->values() : collect();
            $tpmAggregate = $serial && isset($tpmAggregates[$serial]) ? $tpmAggregates[$serial] : null;
            $reportData = $serial && isset($reportRecords[$serial]) ? $reportRecords[$serial] : null;

            // Helper to get row data by title
            $getRowData = fn($title) => collect($layerArray)->firstWhere('rowTitle', $title)['data'] ?? '';

            $magProp = $reportData && $reportData->magnetic_property_data ? json_decode($reportData->magnetic_property_data, true) : [];

            // -----------------------------
            // Layer ordinal conversion
            // -----------------------------
            $layerNumberStr = str_replace('_', '.', str_replace('layer_', '', $layerCol));

            if (strpos($layerNumberStr, '.') !== false) {
                $layerOrdinal = $layerNumberStr . 'th';
            } else {
                $num = (int) $layerNumberStr;
                $suffix = match($num % 10) {
                    1 => ($num % 100 === 11 ? 'th' : 'st'),
                    2 => ($num % 100 === 12 ? 'th' : 'nd'),
                    3 => ($num % 100 === 13 ? 'th' : 'rd'),
                    default => 'th',
                };
                $layerOrdinal = $num . $suffix;
            }

            $layerOrdinal = (string)$layerNumber;

            // Fetch remarks & special instructions for this mass production and layer
            $smpData = SmpData::where('mass_prod', $massProdData->mass_prod)
                ->where('layer', $layerOrdinal)
                ->first();

            $filmPastingData = FilmPastingData::where('mass_prod', $massProdData->mass_prod)
                ->where('layer', $layerOrdinal)
                ->first();

            /*Log::info('SMP DATA RESULT', [
                'mass_prod' => $massProdData->mass_prod,
                'layer' => $layerOrdinal,
                'found' => $smpData ? true : false,
                'remarks' => $smpData->remarks ?? null,
            ]);*/

            if ($formatType === 'Film Pasting') {
                $flattenedLayers[$layerCol] = [
                    'MPI_Date' => '',
                    'Pulse_Tracer_Machine' => $tpmData[0]->Tracer ?? '',
                    'Furnace_Cycle_No' => $massProdData->cycle_no,
                    'Mass_Production' => $massProdData->mass_prod,
                    'Cycle_Pattern' => $massProdData->pattern_no,
                    'Model' => $getRowData('MODEL:')['A'] ?? '',
                    'Lot_No' => $getRowData('LT. No.:')['A'] ?? '',
                    'Layer' => $layerOrdinal,
                    'Batches' => count($tpmData),
                    'Total_Lot_Qty' => $getRowData('TOTAL QTY')['A'] ?? '',
                    'Date' => $coating->date ?? '',
                    'M_C' => $coating->machine_no ?? '',
                    'Magnet_Weight' => $coating->total_magnet_weight ?? '',

                    // Film Pasting fields
                    'Film_Coating_Amount' => $filmPastingData->film_coating_amount ?? '',
                    'Film_Type' => $filmPastingData->film_type ?? '',
                    'Film_Class' => $filmPastingData->film_class ?? '',
                    'blank' => '',

                    // iHc
                    'iHc_Target' => $magProp['ihcStandard'] ?? '',
                    'iHc_Max' => $tpmAggregate && $tpmAggregate->maximum ? json_decode($tpmAggregate->maximum, true)['iHc'] ?? '' : '',
                    'iHc_Min' => $tpmAggregate && $tpmAggregate->minimum ? json_decode($tpmAggregate->minimum, true)['iHc'] ?? '' : '',
                    'iHc_Ave' => $tpmAggregate && $tpmAggregate->average ? json_decode($tpmAggregate->average, true)['iHc'] ?? '' : '',

                    'Remarks' => $smpData->remarks ?? '',
                    'Status' => $reportData->smp_judgement ?? '',
                    'HT_Trouble' => $massProdData->current_pattern === null || $massProdData->current_pattern === ''
                        ? null
                        : ($massProdData->current_pattern === 'PASS' ? 'NO' : 'YES'),
                    'Special_Instruction' => $smpData->special_instruction ?? '',
                    'format_type' => $formatType,
                ];
            } else {
                $flattenedLayers[$layerCol] = [
                    'MPI_Date' => '',
                    'Pulse_Tracer_Machine' => $tpmData[0]->Tracer ?? '',
                    'Furnace_Cycle_No' => $massProdData->cycle_no,
                    'Mass_Production' => $massProdData->mass_prod,
                    'Cycle_Pattern' => $massProdData->pattern_no,
                    'Model' => $getRowData('MODEL:')['A'] ?? '',
                    'Lot_No' => $getRowData('LT. No.:')['A'] ?? '',
                    'Layer' => $layerOrdinal,
                    'Batches' => count($tpmData),
                    'Total_Lot_Qty' => $getRowData('TOTAL QTY')['A'] ?? '',
                    'Date' => $coating->date ?? '',
                    'M_C' => $coating->machine_no ?? '',
                    'Magnet_Weight' => $coating->total_magnet_weight ?? '',

                    // Coating fields
                    'Coating_Target' => $coating->min_tb_content ?? '',
                    'Coating_Max' => $coating->maximum ?? '',
                    'Coating_Min' => $coating->minimum ?? '',
                    'Coating_Ave' => $coating->average ?? '',

                    // iHc
                    'iHc_Target' => $magProp['ihcStandard'] ?? '',
                    'iHc_Max' => $tpmAggregate && $tpmAggregate->maximum ? json_decode($tpmAggregate->maximum, true)['iHc'] ?? '' : '',
                    'iHc_Min' => $tpmAggregate && $tpmAggregate->minimum ? json_decode($tpmAggregate->minimum, true)['iHc'] ?? '' : '',
                    'iHc_Ave' => $tpmAggregate && $tpmAggregate->average ? json_decode($tpmAggregate->average, true)['iHc'] ?? '' : '',

                    'Remarks' => $smpData->remarks ?? '',
                    'Status' => $reportData->smp_judgement ?? '',
                    'HT_Trouble' => $massProdData->current_pattern === null || $massProdData->current_pattern === ''
                        ? null
                        : ($massProdData->current_pattern === 'PASS' ? 'NO' : 'YES'),
                    'Special_Instruction' => $smpData->special_instruction ?? '',
                    'format_type' => $formatType,
                ];
            }
        }

        return response()->json([
            'layersData' => $flattenedLayers,
            'format_type' => $formatType,
        ]);
    }

}
