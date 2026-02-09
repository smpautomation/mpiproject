<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use App\Models\ReportData;
use App\Models\Coating;
use App\Models\MassProduction;
use App\Models\ExcessLayers;
use App\Models\GbdpSecondCoating;
use App\Models\GbdpSecondHeatTreatment;
use App\Models\FilmPastingData;
use App\Models\TPMDataCategory;
use App\Models\TPMData;
use App\Models\TPMDataAggregateFunctions;
use App\Models\NormalSecAdditionals;
use App\Models\NSAAggregateFunctions;
use App\Models\NSACategory;
use App\Models\VtModel;
use App\Models\CpkIhcModel;
use App\Models\GxModel;
use App\Models\TtmncModel;
use App\Models\BhModel;
use App\Models\RobModel;
use App\Models\BreaklotCoating;
use App\Models\BreaklotInitialLot;
use App\Models\BreaklotSecondCoating;
use App\Models\BreaklotFilmpasting;

class BackEndPdfController extends Controller
{

    public function apiGenerateAndSave($serial)
    {
        $this->generateAndMerge($serial); // already handles validation + save
        return response()->json(['message' => 'PDF saved successfully']);
    }

    public function generateAndMerge($serial)
    {
        $chartFilename = "chart_{$serial}.png";
        $chartPath = public_path("charts/{$chartFilename}");

        // Optional: check if image exists
        if (!file_exists($chartPath)) {
            // Fallback or skip image — up to you
            $chartFilename = null;
        }

        $reportData = ReportData::where('tpm_data_serial', $serial)->first();
        if (!$reportData) {
            return response()->json([
                'status' => false,
                'message' => "Report Data with serial: {$serial} not found",
            ], 404);
        }
        // Prepare data for the PDF views
        $magneticProperty = json_decode($reportData->magnetic_property_data, true);
        $brBounds = ['lower' => null, 'upper' => null];
        if (!empty($magneticProperty['brStandard']) && str_contains($magneticProperty['brStandard'], '~')) {
            [$lower, $upper] = explode('~', $magneticProperty['brStandard']);
            $brBounds['lower'] = trim($lower);
            $brBounds['upper'] = trim($upper);
        }
        // Calculate BR variance (if values exist)
        $brVariance = null;
        $ihcVariance = null;
        $ihkVariance = null;
        if (
            isset($magneticProperty['brMaximum'], $magneticProperty['brMinimum'], $magneticProperty['ihcMaximum'], $magneticProperty['ihcMinimum'], $magneticProperty['ihkMaximum'], $magneticProperty['ihkMinimum'])
        ) {
            $brVariance = $magneticProperty['brMaximum'] - $magneticProperty['brMinimum'];
            $ihcVariance = $magneticProperty['ihcMaximum'] - $magneticProperty['ihcMinimum'];
            $ihkVariance = $magneticProperty['ihkMaximum'] - $magneticProperty['ihkMinimum'];
        }

        $preparedByDate = null;
        $checkedByDate = null;
        $approvedByDate = null;

        if(!empty($reportData->created_at)){
            $reportDate = \Carbon\Carbon::parse($reportData->created_at)->format('Y-m-d');
        }

        if (!empty($reportData->prepared_by_date)) {
            $preparedByDate = \Carbon\Carbon::parse($reportData->prepared_by_date)->format('Y-m-d');
        }

        if (!empty($reportData->checked_by_date)) {
            $checkedByDate = \Carbon\Carbon::parse($reportData->checked_by_date)->format('Y-m-d');
        }

        if (!empty($reportData->approved_by_date)) {
            $approvedByDate = \Carbon\Carbon::parse($reportData->approved_by_date)->format('Y-m-d');
        }

        $preparedFirstFontSize = $this->resolveFontSize($reportData->prepared_by_firstname ?? '');
        $preparedLastFontSize  = $this->resolveFontSize($reportData->prepared_by_surname ?? '');
        $checkedFirstFontSize = $this->resolveFontSize($reportData->checked_by_firstname ?? '');
        $checkedLastFontSize  = $this->resolveFontSize($reportData->checked_by_surname ?? '');
        $approvedFirstFontSize = $this->resolveFontSize($reportData->approved_by_firstname ?? '');
        $approvedLastFontSize  = $this->resolveFontSize($reportData->approved_by_surname ?? '');

        // === SORTED NOTE REASON LOGIC ===
        $noteReasonRaw = json_decode($reportData->note_reason_reject ?? '[]', true);

        // Fallback if not an array (plain text)
        if (!is_array($noteReasonRaw)) {
            $noteReasonRaw = explode("\n", $reportData->note_reason_reject);
            $noteReasonRaw = array_filter(array_map('trim', $noteReasonRaw));
        }

        // Define extended priority order (lower number = higher priority)
        $priorityOrder = [
            '- LOW BR'                    => 1,
            '- HIGH BR'                   => 1,
            '- N.G iHc'                   => 2,
            '- iHc Below Target+500 Oe'   => 2,
            '- N.G iHk'                   => 3,
            '- N.G Hr95'                  => 4,
            '- N.G Hr98'                  => 5,
            '- N.G iHc-iHk'               => 6,
            '- N.G Br-4PIa'               => 7,
            '- N.G bHc'                   => 8,
        ];

        // Sort using priority
        usort($noteReasonRaw, function ($a, $b) use ($priorityOrder) {
            $aPriority = $priorityOrder[$a] ?? 99;
            $bPriority = $priorityOrder[$b] ?? 99;
            return $aPriority - $bPriority;
        });

        $tpmCategories = TPMDataCategory::where('tpm_data_serial', $serial)->first();
        $tpmData = TPMData::where('serial_no', $serial)->first();
        if (!$tpmCategories || !$tpmData) {
            return response()->json([
                'status' => false,
                'message' => "TPM Data for serial: {$serial} is invalid or missing",
            ], 404);
        }
        $tpmDataAll = TPMData::with('remark')
            ->where('serial_no', $serial)
            ->orderBy('zone', 'asc') // ensure zone order is consistent
            ->get();
        if ($tpmDataAll->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => "TPM Data for serial: {$serial} is invalid or missing",
            ], 404);
        }
        //dd($tpmDataAll);

        $massprod = $tpmData->mass_prod;
        $layer = $tpmData->layer_no;
        $furnace = $tpmData->furnace;
        $addtnlModel = $tpmCategories->actual_model;
        $addtnlLot = $tpmCategories->jhcurve_lotno;

        $massProdData = MassProduction::query()
            ->where('mass_prod', $massprod)
            ->where('furnace', $furnace)
            ->first();

        if (!$massProdData) {
            return response()->json([
                'status' => false,
                'message' => "MassProduction record not found for mass_prod: {$massprod} and furnace: {$furnace}"
            ], 404);
        }

        // Build column name dynamically
        $columnLayerData = 'layer_' . str_replace('.', '_', $layer);
        $columnLayerFormat = 'layer_' . str_replace('.', '_', $layer) . '_format_type';
        //dd($columnLayerFormat);
        // Fetch the value safely
        $gbdp_report_format_type = $massProdData->$columnLayerFormat ?? null;
        $control_sheet_data = $massProdData->$columnLayerData ?? null;

        if (!$gbdp_report_format_type) {
            return response()->json([
                'status' => false,
                'message' => "No format type found for layer: {$layer}"
            ], 404);
        }

        // Check if there is a matching Initial Lot
        $initialLotExists =
            BreaklotCoating::where([
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->exists()
            ||
            BreaklotSecondCoating::where([
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->exists();
            /*
        if ($initialLotExists) {
            // Only proceed if initial lot exists
            if (BreaklotCoating::where([
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->exists()) {
                $gbdp_report_format_type = 'Normal';
            } elseif (BreaklotSecondCoating::where([
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->exists()) {
                $gbdp_report_format_type = '1st and 2nd Gbdp';
            } elseif (BreaklotFilmpasting::where([
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->exists()) {
                $gbdp_report_format_type = 'Film Pasting';
            }
        }*/

        $debug = [
            'input' => [
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ],
            'initial_lot_exists' => $initialLotExists,
            'matches' => [
                'coating'        => false,
                'second_coating' => false,
                'film_pasting'   => false,
            ],
            'final_format_type' => null,
        ];

        if ($initialLotExists) {

            $debug['matches']['coating'] = BreaklotCoating::where([
                'mass_prod' => $massprod,
                'furnace'   => $furnace,
                'layer'     => $layer,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->exists();

            if ($debug['matches']['coating']) {
                $gbdp_report_format_type = 'Normal';
            } else {

                $debug['matches']['second_coating'] = BreaklotSecondCoating::where([
                    'mass_prod' => $massprod,
                    'furnace'   => $furnace,
                    'layer'     => $layer,
                    'model'     => $addtnlModel,
                    'lot_no'    => $addtnlLot,
                ])->exists();

                if ($debug['matches']['second_coating']) {
                    $gbdp_report_format_type = '1st and 2nd Gbdp';
                } else {

                    $debug['matches']['film_pasting'] = BreaklotFilmpasting::where([
                        'mass_prod' => $massprod,
                        'furnace'   => $furnace,
                        'layer'     => $layer,
                        'model'     => $addtnlModel,
                        'lot_no'    => $addtnlLot,
                    ])->exists();

                    if ($debug['matches']['film_pasting']) {
                        $gbdp_report_format_type = 'Film Pasting';
                    }
                }
            }
        }

        $debug['final_format_type'] = $gbdp_report_format_type;

        //dd($debug);


        // Decode JSON string
        $controlSheetArray = json_decode($control_sheet_data, true);
        if (!$controlSheetArray) {
            return response()->json([
                'status' => false,
                'message' => "Control sheet data is invalid"
            ], 404);
        }

        // Map rowTitles to clean variable names
        $cs = [];
        foreach ($controlSheetArray as $row) {
            $title = strtolower($row['rowTitle']);            // lowercase
            $title = preg_replace('/[^a-z0-9]+/', '_', $title); // replace non-alphanumeric with _
            $title = trim($title, '_');                        // remove trailing/leading underscores
            $cs[$title] = $row['data'];
        }

        // Create variables dynamically
        foreach ($cs as $key => $value) {
            ${"cs_{$key}"} = $value;
        }

        if (isset($cs_lt_no) && is_array($cs_lt_no)) { //Removes rogue lot numbers...
            $cs_lt_no = array_filter($cs_lt_no, function ($lot) use ($addtnlLot) {
                return $lot === $addtnlLot;
            });
        }

        if (isset($cs_lt_no) && is_array($cs_lt_no) && isset($cs_total_qty) && is_array($cs_total_qty)) { //Precise total qty
            $matchingLetters = array_keys($cs_lt_no);

            if (!empty($matchingLetters) && isset($cs_total_qty) && is_array($cs_total_qty)) {
                $firstMatchingLetter = $matchingLetters[0];
                $cs_total_qty = $cs_total_qty[$firstMatchingLetter] ?? null;
            }
        }

        // ================= EXCESS LAYER LOT SCAN =================
        $controlLotNoExcess = [];

        $cs_lt_no_final = $cs_lt_no['A'] ?? $cs_lt_no['B'] ?? null;

        if ($cs_lt_no_final) {

            $excessLayers = ExcessLayers::where('mass_prod', $massprod)
                ->where('furnace', $massProdData->furnace)
                ->where('layer', '!=', $layer)
                ->orderBy('layer')
                ->get();

            foreach ($excessLayers as $excess) {

                $layerData = $excess->layer_data;
                if (!is_array($layerData)) continue;

                foreach ($layerData as $row) {

                    if (trim($row['rowTitle']) === 'LT. No.:') {

                        $rowMap = [];

                        foreach ($row['data'] as $box => $lotNo) {
                            if ((string)$lotNo === (string)$cs_lt_no_final) {
                                $rowMap[$box] = $lotNo; // actual LOT NO
                            }
                        }

                        if (!empty($rowMap)) {
                            $controlLotNoExcess[$excess->layer] = $rowMap;
                        }

                        break;
                    }
                }
            }
        }


        //Coating data --------------- Coating data --------------- Coating data --------------- Coating data
        if ($initialLotExists) {
            $coatingData = BreaklotCoating::where([
                'mass_prod' => $massprod,
                'layer'     => $layer,
                'furnace'   => $furnace,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->first();
        } else {
            $coatingData = Coating::where([
                'mass_prod' => $massprod,
                'layer'     => $layer,
                'furnace'   => $furnace,
            ])->first();
        }

        $coatingDataJson = $coatingData?->coating_data ?? null;
        $coatingDataValues = json_decode($coatingDataJson, true);

        //Coating data --------------- Coating data --------------- Coating data --------------- Coating data End

        //Film Pasting --------------- Film Pasting --------------- Film Pasting --------------- Film Pasting

        if ($initialLotExists) {
            $filmPastingData = BreaklotFilmpasting::where([
                'mass_prod' => $massprod,
                'layer'     => $layer,
                'furnace'   => $furnace,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->first();
        } else {
            $filmPastingData = FilmPastingData::where([
                'mass_prod' => $massprod,
                'layer'     => $layer,
                'furnace'   => $furnace,
            ])->first();
        }

        $filmPastingHLineValues = $filmPastingData?->h_line_parameters ?? null;
        $filmPastingTLineValues = $filmPastingData?->t_line_parameters ?? null;

        //Film Pasting --------------- Film Pasting --------------- Film Pasting --------------- Film Pasting End

        //Second Gbdp ------------------ Second Gbdp ------------------ Second Gbdp ------------------ Second Gbdp

       if ($initialLotExists) {
            $secondGbdpCoatingData = BreaklotSecondCoating::where([
                'mass_prod' => $massprod,
                'layer'     => $layer,
                'furnace'   => $furnace,
                'model'     => $addtnlModel,
                'lot_no'    => $addtnlLot,
            ])->first();
        } else {
            $secondGbdpCoatingData = GbdpSecondCoating::where([
                'mass_prod' => $massprod,
                'layer'     => $layer,
                'furnace'   => $furnace,
            ])->first();
        }

        /*$secondGbdpHTData = GbdpSecondHeatTreatment::where('mass_prod', $massprod)
            ->where('layer', $layer)
            ->first();
        if (!$secondGbdpHTData) {
            return response()->json([
                'status' => false,
                'message' => "Second GBDP Heat Treatment record not found for mass_prod: {$massprod} or layer: {$layer}"
            ], 404);
        }*/

        //Second Gbdp ------------------ Second Gbdp ------------------ Second Gbdp ------------------ Second Gbdp End

        $onlyFurnacePrefix = explode('-', $tpmData->sintering_furnace_no)[0] ?? null;
        $onlyFurnacePostfix = explode('-', $tpmData->sintering_furnace_no)[1] ?? null;

        $tpmDataAggregate = TPMDataAggregateFunctions::where('tpm_data_serial', $serial)->first();
        if (!$tpmDataAggregate) {
            return response()->json([
                'status' => false,
                'message' => "TPM Aggregate Data for serial: {$serial} is invalid or missing",
            ], 404);
        }
        $aggregateMaximum = json_decode($tpmDataAggregate->maximum, true); // now an associative array
        $aggregateMinimum = json_decode($tpmDataAggregate->minimum, true); // same
        $aggregateAverage = json_decode($tpmDataAggregate->average, true); // same

        $noteReasonRaw = json_decode($reportData->note_reason_reject ?? '[]', true);
        //dd($noteReasonRaw);
        // fallback if it’s malformed (just a string with newlines instead of valid JSON)
        if (!is_array($noteReasonRaw)) {
            $noteReasonRaw = explode("\n", $reportData->note_reason_reject);
            $noteReasonRaw = array_filter(array_map('trim', $noteReasonRaw));
        }

        $d1x1x1     = json_decode($reportData->data_1x1x1_info ?? '[]', true);
        $dVT        = json_decode($reportData->data_VT_info ?? '[]', true);
        $diHc_cpk   = json_decode($reportData->data_iHc_cpk_info ?? '[]', true);
        $dGX        = json_decode($reportData->data_GX_info ?? '[]', true);
        $dBH        = json_decode($reportData->data_bh_info ?? '[]', true);
        $dROB       = json_decode($reportData->data_ROB_info ?? '[]', true);

        $model = $tpmCategories->actual_model ?? '';
        $noteReasons = $noteReasonRaw;

        $MODELS_SHOW_VT_DATA = VtModel::pluck('model_name')->toArray();
        $MODELS_SHOW_CPK     = CpkIhcModel::pluck('model_name')->toArray();
        $MODELS_SHOW_GX      = GxModel::pluck('model_name')->toArray();
        $MODELS_1X1X1_NO_CORNER = TtmncModel::pluck('model_name')->toArray();
        $MODELS_SHOW_BH      = BhModel::pluck('model_name')->toArray();
        $MODELS_SHOW_ROB     = RobModel::pluck('model_name')->toArray();

        $showROB         = in_array($model, $MODELS_SHOW_ROB);
        $hasNGihc        = in_array('- N.G iHc', $noteReasons);

        $showCpkFrom_iHc = $hasNGihc && in_array($model, $MODELS_SHOW_CPK);
        $showGX          = $hasNGihc && in_array($model, $MODELS_SHOW_GX);
        $showBHData      = $hasNGihc && in_array($model, $MODELS_SHOW_BH);

        $showVTData         = false; // default to false
        $showVTData_default = false; // default to false

        // VT Logic
        if ($hasNGihc && in_array($model, $MODELS_SHOW_VT_DATA)) {
            if (!empty($dVT['sample_qty']) && $dVT['sample_qty'] > 0) {
                $showVTData = true;
            } else {
                $showVTData_default = true;
            }
        }

        // 1x1x1 Data Conditions
        $show1x1x1Data_withoutCorner = false;
        $show1x1x1Data_Corner        = false;
        $isTTM_model                 = str_contains($model, 'TTM');

        if ($isTTM_model && $hasNGihc) {
            $show1x1x1Data_withoutCorner = true;
            $show1x1x1Data_Corner = true;

            if (in_array($model, $MODELS_1X1X1_NO_CORNER)) {
                $show1x1x1Data_Corner = false;
            }
        }
        //dd($show1x1x1Data_Corner);

        //NSA Additionals --------------- NSA Additionals --------------- NSA Additionals
        $nsaData = NormalSecAdditionals::where('serial_no', $serial)->first();

        // group rows by set_no (ordered)
        $nsaGroups = NormalSecAdditionals::where('serial_no', $serial)
            ->orderBy('set_no')
            ->get()
            ->groupBy('set_no'); // collection keyed by set_no

        $nsaAggregateFunctions = NSAAggregateFunctions::where('nsa_serial', $serial)
            ->get()
            ->keyBy('nsa_set'); // now keyed by set number

        //Refactor: Sort the data by zone column --- 1/23/2026
        foreach ($nsaGroups as $setNo => $rows) {
            $nsaGroups[$setNo] = $rows->sort(function ($a, $b) {
                $parse = function($zone) {
                    if (preg_match('/^(\d+)([A-Z]+)(\d+)$/i', $zone, $matches)) {
                        return [
                            'n1' => (int)$matches[1],
                            'letters' => $matches[2],
                            'n2' => (int)$matches[3],
                        ];
                    }
                    return ['n1' => 0, 'letters' => $zone, 'n2' => 0];
                };

                $A = $parse($a->zone ?? '');
                $B = $parse($b->zone ?? '');

                if ($A['n1'] !== $B['n1']) return $A['n1'] <=> $B['n1'];
                if ($A['letters'] !== $B['letters']) return strcmp($A['letters'], $B['letters']);
                return $A['n2'] <=> $B['n2'];
            })->values(); // reset keys
        }

        // Directly assign aggregates from DB, no computation
        $nsaAggregates = [];
        // --- NSA chart filenames setup ---
        $nsaChartFilenames = [];

        foreach ($nsaGroups as $setNo => $rows) {
            $chartFile = "chart_{$serial}_set{$setNo}.png";
            $chartPath = public_path("sec_charts/{$chartFile}");

            $nsaChartFilenames[$setNo] = file_exists($chartPath) ? $chartFile : null;

            if (isset($nsaAggregateFunctions[$setNo])) {
                $func = $nsaAggregateFunctions[$setNo];
                $nsaAggregates[$setNo]['average'] = json_decode($func->average, true) ?? [];
                $nsaAggregates[$setNo]['max']     = json_decode($func->maximum, true) ?? [];
                $nsaAggregates[$setNo]['min']     = json_decode($func->minimum, true) ?? [];
            } else {
                $nsaAggregates[$setNo]['average'] = [];
                $nsaAggregates[$setNo]['max']     = [];
                $nsaAggregates[$setNo]['min']     = [];
            }
        }

        $nsaCategory = NSACategory::where('nsa_serial', $serial)->first();

        $nsa_onlyFurnacePrefix  = isset($nsaData->sintering_furnace_no) ? explode('-', $nsaData->sintering_furnace_no)[0] ?? null : null;
        $nsa_onlyFurnacePostfix = isset($nsaData->sintering_furnace_no) ? explode('-', $nsaData->sintering_furnace_no)[1] ?? null : null;

        // how many sets we have
        $nsaSetCount = $nsaGroups->count();
        //dd($nsaChartFilenames);
        //dd($nsaAggregateMaximum);


        //NSA Additionals --------------- NSA Additionals --------------- NSA Additionals End

        $data = [
            'massProd' => $massprod,
            'layer' => $layer,
            'serial' => $serial,
            'chartFilename' => $chartFilename, // pass to Blade
            'nsaChartFilenames' => $nsaChartFilenames, // per-NSA-set charts
            'reportData' => $reportData, // pass report data to Blade
            'magneticProperty' => $magneticProperty,
            'brBounds' => $brBounds,
            'brVariance' => $brVariance,
            'ihcVariance' => $ihcVariance,
            'ihkVariance' => $ihkVariance,
            'coatingData' => $coatingData ?? null, // pass coating data to Blade
            'coatingDataValues' => $coatingDataValues ?? null,
            'secondGbdp2ndCoatingData' => $secondGbdpCoatingData['coating_data_2ndgbdp'] ?? null,
            'secondGbdp2ndCoatingInfo' => $secondGbdpCoatingData['coating_info_2ndgbdp'] ?? null,
            'secondGbdpCoatingData' => $secondGbdpCoatingData ?? null,
            'heatTreatmentData' => $massProdData, // pass heat treatment data to Blade
            'filmPastingData' => $filmPastingData ?? null,
            'filmPastingHLine' => $filmPastingHLineValues ?? null,
            'filmPastingTLine' => $filmPastingTLineValues ?? null,
            'controlModel' => $cs_model,
            'controlCoatingMCNo' => $cs_coating_m_c_no,
            'controlLotNo' => $cs_lt_no,
            'controlLotNoExcess' => $controlLotNoExcess,
            'controlQty' => $cs_qty_pcs,
            'controlHt' => $cs_ht_pcs,
            'controlLt' => $cs_lt_pcs,
            'controlCoating' => $cs_coating,
            'controlWeight' => $cs_wt_kg,
            'controlBoxNo' => $cs_box_no,
            'controlMagnetPreparedBy' => $cs_magnet_prepared_by,
            'controlBoxPreparedBy' => $cs_box_prepared_by,
            'controlTotalQty' => $cs_total_qty,
            'preparedByDate' => $preparedByDate, // pass prepared by
            'checkedByDate' => $checkedByDate, // pass checked by date
            'approvedByDate' => $approvedByDate, // pass approved by date
            'preparedFirstFontSize' => $preparedFirstFontSize, // pass font size for first name
            'preparedLastFontSize' => $preparedLastFontSize, // pass font size for
            'checkedFirstFontSize' => $checkedFirstFontSize, // pass font size for checked first name
            'checkedLastFontSize' => $checkedLastFontSize, // pass font size for
            'approvedFirstFontSize' => $approvedFirstFontSize, // pass font size for approved first name
            'approvedLastFontSize' => $approvedLastFontSize, // pass font size for
            'noteReasonsSorted' => $noteReasonRaw, // pass sorted note reasons
            //'coatingDetails' => $coatingDetails, // pass coating details
            'tpmCat' => $tpmCategories, // pass TPM categories
            'tpmData' => $tpmData, // pass single TPM data
            'tpmDataAll' => $tpmDataAll, // pass all TPM data
            'miasEmp' => $massProdData->mias_emp,
            'factorEmp' => $massProdData->factor_emp,
            //'magnetBoxLocation' => $MBL, // pass magnet box location
            'reportDate' => $reportDate ?? null, // pass report date
            'sinteringFurnaceNo' => $onlyFurnacePrefix, // pass only furnace prefix
            'sinteringNo' => $onlyFurnacePostfix, // pass only furnace postfix
            'tpmAggregateMax' => $aggregateMaximum, // pass TPM aggregate maximum
            'tpmAggregateMin' => $aggregateMinimum, // pass TPM aggregate minimum
            'tpmAggregateAvg' => $aggregateAverage, // pass TPM aggregate average
            'flags' => [
                'showROB' => $showROB,
                'showVTData' => $showVTData,
                'showVTData_default' => $showVTData_default,
                'showCpkFrom_iHc' => $showCpkFrom_iHc,
                'showGX' => $showGX,
                'showBHData' => $showBHData,
                'show1x1x1Data_withoutCorner' => $show1x1x1Data_withoutCorner,
                'show1x1x1Data_Corner' => $show1x1x1Data_Corner,
                'isTTM_model' => $isTTM_model,
            ],
            'modelData' => [
                'gx' => $dGX,
                'vt' => $dVT,
                'ihc_cpk' => $diHc_cpk,
                'bh' => $dBH,
                'rob' => $dROB,
                'd1x1x1' => $d1x1x1,
            ],
            'nsaData'       => $nsaData,
            'nsaGroups'     => $nsaGroups,
            'nsaAggregates' => $nsaAggregates,
            'nsaSetCount'   => $nsaSetCount,
            'nsaCategory'   => $nsaCategory,
            'nsa_sinteringFurnaceNo' => $nsa_onlyFurnacePrefix, // pass only furnace prefix
            'nsa_sinteringNo' => $nsa_onlyFurnacePostfix, // pass only furnace postfix
            'nsa_mias' => $nsaCategory->mias_emp ?? null,
            'nsa_factor' => $nsaCategory->factor_emp ?? null,
        ];

        // Decide which portrait view to use
        switch ($gbdp_report_format_type) {
            case 'Normal':
                $portraitView = 'pdf.report_page1_portrait';
                break;
            case 'Film Pasting':
                $portraitView = 'pdf.report_page1_filmpasting_portrait';
                break;
            case '1st and 2nd Gbdp':
                $portraitView = 'pdf.report_page1_second_gbdp_portrait';
                break;
            default:
                throw new \Exception("Unknown report format type: {$gbdp_report_format_type}");
        }

        // Generate portrait
        $portrait = Pdf::loadView($portraitView, $data)
            ->setPaper('a4', 'portrait')
            ->output();

        // Landscape stays the same
        $landscape = Pdf::loadView('pdf.report_page2_landscape', $data)
            ->setPaper('a4', 'landscape')
            ->output();

        $merger = new Merger();
        $merger->addRaw($portrait);
        $merger->addRaw($landscape);


        // Conditionally add secondary landscape page if NSA data exists
        if ($nsaData) {
            foreach ($nsaGroups->chunk(2) as $chunkSets) { // chunk size = 3 sets at a time
                $chunkData = $data;
                $chunkData['nsaGroups'] = $chunkSets;

                $secLandscapeChunk = Pdf::loadView('pdf.report_page2_sec_additional_landscape', $chunkData)
                    ->setPaper('a4', 'landscape')
                    ->output();

                $merger->addRaw($secLandscapeChunk);

                // Free memory immediately
                unset($secLandscapeChunk, $chunkData);
                gc_collect_cycles();
            }
        }


        $mergedPdf = $merger->merge();

        $massProd = $massprod ?? 'unknown';
        $furnace = str_replace('-', '', $massProdData->furnace);
        if ($initialLotExists) {
            // Use selected SET (additional) values
            $cs_model_final = $addtnlModel;
            $cs_lt_no_final = $addtnlLot;
        } else {
            // Default behavior (A → B fallback)
            $cs_model_final = $cs_model['A'] ?? $cs_model['B'] ?? null;
            $cs_lt_no_final = $cs_lt_no['A'] ?? $cs_lt_no['B'] ?? null;
        }
        $rawFilename = "({$reportData->smp_judgement}) {$cs_model_final} Lot No {$cs_lt_no_final}";
        //dd($rawFilename);

        $savedPath = $this->saveMergedPdf($massprod, $rawFilename, $mergedPdf, $furnace);

        return response($mergedPdf, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');
    }


    private function resolveFontSize(string $name): string
    {
        if (empty(trim($name))) return '14px';

        $length = strlen(trim($name));

        return match (true) {
            $length <= 4  => '15px',
            $length === 5 => '14px',
            $length === 6 => '13px',
            $length === 7 => '12px',
            $length === 8 => '11px',
            $length === 9 => '10px',
            $length === 10 => '9px',
            $length === 11 => '8px',
            $length === 12 => '7px',
            $length === 13 => '6px',
            default       => '7px',
        };
    }

    private function saveMergedPdf(string $massProd, string $rawFilename, string $pdfContent, string $furnace): string
    {
        $massProd = preg_replace('/[^A-Za-z0-9\-\s#]/', '_', $massProd);
        $safeBaseName = preg_replace('/[^A-Za-z0-9\-\s\(\)#]/', '_', $rawFilename);
        $safeBaseName = preg_replace('/[\/\s]+/', '_', $safeBaseName);

        $fileName = $safeBaseName . '.pdf';
        $destinationPath = public_path("files/{$furnace} {$massProd}");
        //dd($destinationPath);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        file_put_contents("{$destinationPath}/{$fileName}", $pdfContent);

        return "files/{$massProd}/{$fileName}";
    }
}
