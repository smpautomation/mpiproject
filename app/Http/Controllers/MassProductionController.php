<?php

namespace App\Http\Controllers;

use App\Models\MassProduction;
use App\Models\GbdpSecondCoating;
use App\Models\GbdpSecondHeatTreatment;
use App\Models\FilmPastingData;
use App\Models\Coating;
use Illuminate\Http\Request;
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
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9.5' => 'nullable|json',
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
            'layer_9.5_serial' => 'nullable|integer',
        ]);

        return MassProduction::create($validated);
    }

    public function show($id)
    {
        return MassProduction::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $production = MassProduction::findOrFail($id);

        $validated = $request->validate([
            'mass_prod' => 'required|string|unique:mass_productions,mass_prod,' . $id,
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
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9.5' => 'nullable|json',
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
            'layer_9.5_serial' => 'nullable|integer',
        ]);

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
            'loader' => 'nullable|string',
            'unloader' => 'nullable|string',
            'box_condition' => 'nullable|string',
            'box_cover' => 'nullable|string',
            'box_arrangement' => 'nullable|string',
            'encoded_by' => 'nullable|string',
            'remarks1' => 'nullable|string',
            'remarks2' => 'nullable|string',
            'remarks3' => 'nullable|string',
            'layer_1' => 'nullable|json',
            'layer_2' => 'nullable|json',
            'layer_3' => 'nullable|json',
            'layer_4' => 'nullable|json',
            'layer_5' => 'nullable|json',
            'layer_6' => 'nullable|json',
            'layer_7' => 'nullable|json',
            'layer_8' => 'nullable|json',
            'layer_9' => 'nullable|json',
            'layer_9.5' => 'nullable|json',
            'grand_total_weight' => 'nullable|numeric',
            'grand_total_quantity' => 'nullable|integer',
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

        foreach ($layers as $column => $label) {
            $value = $record->$column;

            if (!empty($value) && $value !== 'null') {
                $decoded = json_decode($value, true);
                if (!empty($decoded)) {
                    $completed[] = $label;
                }
            }
        }

        return response()->json([
            'completed_layers' => $completed,
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

}
