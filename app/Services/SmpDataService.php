<?php

namespace App\Services;

use App\Models\MassProduction;
use App\Models\TPMData;
use App\Models\TPMDataCategory;
use App\Models\BreaklotCoating;
use App\Models\BreaklotSecondCoating;
use App\Models\BreaklotInitialLotHt;
use App\Models\BreaklotFilmpasting;
use App\Models\TPMDataAggregateFunctions;
use App\Models\ReportData;
use App\Models\Coating;
use App\Models\SmpData;
use App\Models\FilmPastingData;
use Illuminate\Support\Facades\Log;

class SmpDataService
{
    /**
     * Flatten SMP data for a given furnace + mass production.
     */
    public function getFlattenedData(string $furnace, string $massProd): array
    {
        Log::info("Fetching MassProduction for furnace={$furnace}, massProd={$massProd}");

        $massProdData = MassProduction::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->first();

        if (!$massProdData) {
            Log::warning("MassProduction not found: furnace={$furnace}, massProd={$massProd}");
            return [];
        }

        $attributes = collect($massProdData->getAttributes());

        // Extract layer JSON columns
        $layersData = $attributes
            ->filter(fn($value, $key) => preg_match('/^layer_\d+(_\d+)?$/', $key) && $value !== null)
            ->mapWithKeys(fn($value, $key) => [$key => @json_decode($value, true) ?: $value]);

        // Preload per-layer serials for TPM
        $layerSerials = $attributes
            ->filter(fn($value, $key) => preg_match('/^layer_\d+(_\d+)?_serial$/', $key) && $value !== null)
            ->values()
            ->all();

        //$tpmAggregates = TPMDataAggregateFunctions::whereIn('tpm_data_serial', $layerSerials)->get()->keyBy('tpm_data_serial');
        //$reportRecords = ReportData::whereIn('tpm_data_serial', $layerSerials)->get()->keyBy('tpm_data_serial');
        Log::info("QUERY START: Coating", [
            'furnace' => $furnace,
            'mass_prod' => $massProd
        ]);

        $coatingQuery = Coating::where('furnace', $furnace)
            ->where('mass_prod', $massProd);

        Log::info("SQL: Coating", [
            'sql' => $coatingQuery->toSql(),
            'bindings' => $coatingQuery->getBindings(),
        ]);

        $coatings = $coatingQuery->get();

        Log::info("QUERY RESULT: Coating", [
            'count' => $coatings->count(),
            'layers_found' => $coatings->pluck('layer')->values()->all(),
        ]);

        $coatings = $coatings->keyBy(fn($c) => $c->layer);

        // Step 1: Build unique Model+Lot mapping per layer
        $mappedRows = [];
        foreach ($layersData as $layerCol => $layerArray) {
            $layerNumber = (float) str_replace('_', '.', str_replace('layer_', '', $layerCol));
            $getRowData = fn($title) => collect($layerArray)->firstWhere('rowTitle', $title)['data'] ?? [];
            $models = $getRowData('MODEL:');
            $lots = $getRowData('LT. No.:');
            $totals = $getRowData('TOTAL QTY');

            $seenPairs = [];
            foreach ($models as $slot => $model) {
                $lotNo = $lots[$slot] ?? null;
                $totalQty = $totals[$slot] ?? null;
                if (!$model || !$lotNo) continue;

                $pairKey = $model . '||' . $lotNo;
                if (!isset($seenPairs[$pairKey])) {
                    $mappedRows[] = [
                        'layer' => $layerNumber,
                        'layerCol' => $layerCol,
                        'model' => $model,
                        'lot_no' => $lotNo,
                        'total_qty' => $totalQty,
                    ];
                    $seenPairs[$pairKey] = true;
                }
            }
        }

        // Step 2: Resolve serial_no using TPMDataCategory validated against TPMData
        $tpmSerialVars = TPMData::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->pluck('serial_no')
            ->unique()
            ->values()
            ->all();

        $modelLotPairs = collect($mappedRows)->map(fn($r) => ['model' => $r['model'], 'lot_no' => $r['lot_no']])->unique();

        $tpmCategoryRows = TPMDataCategory::where(function ($q) use ($modelLotPairs) {
            foreach ($modelLotPairs as $pair) {
                $q->orWhere(function ($sub) use ($pair) {
                    $sub->where('actual_model', $pair['model'])
                        ->where('jhcurve_lotno', $pair['lot_no']);
                });
            }
        })->get();

        $modelLotToSerial = [];
        foreach ($modelLotPairs as $pair) {
            $candidates = $tpmCategoryRows
                ->where('actual_model', $pair['model'])
                ->where('jhcurve_lotno', $pair['lot_no']);

            foreach ($candidates as $row) {
                if (in_array($row->tpm_data_serial, $tpmSerialVars)) {
                    $modelLotToSerial[$pair['model'] . '||' . $pair['lot_no']] = $row->tpm_data_serial;
                    break;
                }
            }
        }

        $allSerials = array_values($modelLotToSerial); // fetch all serial numbers
        $tpmRecords = TPMData::whereIn('serial_no', $allSerials)->get()->groupBy('serial_no');
        $tpmAggregates = TPMDataAggregateFunctions::whereIn('tpm_data_serial', $allSerials)->get()->keyBy('tpm_data_serial');
        $reportRecords = ReportData::whereIn('tpm_data_serial', $allSerials)->get()->keyBy('tpm_data_serial');

        // Step 3: Build final flattened rows
        $flattenedLayers = [];
        foreach ($mappedRows as $mapped) {
            $layerCol = $mapped['layerCol'];
            $layerNumber = $mapped['layer'];
            $layerOrdinal = (string) $layerNumber;
            $model = $mapped['model'];
            $lotNo = $mapped['lot_no'];

            $serial = $modelLotToSerial[$model . '||' . $lotNo] ?? null;
            $formatType = $massProdData->{$layerCol . '_format_type'} ?? null;

            // Determine if breaklot
            $isBreaklot = collect($mappedRows)->where('layer', $layerNumber)->count() > 1;

            // Check if initial lot in breaklot
            $initialLotCheck = BreaklotInitialLotHt::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer', $layerOrdinal)
                ->where('initial_model', $model)
                ->where('initial_lot', $lotNo)
                ->exists();
            $isInitialLot = !$isBreaklot || $initialLotCheck;

            // Coating fallback
            if ($isInitialLot) {
                Log::info("COATING LOOKUP", [
                    'layerNumber_raw' => $layerNumber,
                    'layerNumber_type' => gettype($layerNumber),
                    'available_keys' => $coatings->keys()->values()->all(),
                ]);

                $coating = $coatings->get($layerNumber);

                Log::info("COATING RESULT", [
                    'found' => $coating ? true : false,
                    'layer' => $layerNumber,
                ]);
            } else {
                $coating = BreaklotCoating::where('furnace', $furnace)
                    ->where('mass_prod', $massProd)
                    ->where('layer', $layerOrdinal)
                    ->where('model', $model)
                    ->where('lot_no', $lotNo)
                    ->first();
                if (!$coating) {
                    $coating = BreaklotSecondCoating::where('furnace', $furnace)
                        ->where('mass_prod', $massProd)
                        ->where('layer', $layerOrdinal)
                        ->where('model', $model)
                        ->where('lot_no', $lotNo)
                        ->first();
                }
            }

            // TPM & report data
            $tpmData = $serial && isset($tpmRecords[$serial]) ? $tpmRecords[$serial]->values() : collect();
            $tpmAggregate = $serial && isset($tpmAggregates[$serial]) ? $tpmAggregates[$serial] : null;
            $reportData = $serial && isset($reportRecords[$serial]) ? $reportRecords[$serial] : null;

            // SMP & Film Pasting
            $smpData = SmpData::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer', $layerOrdinal)
                ->first();

            $filmPastingData = FilmPastingData::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer', $layerOrdinal)
                ->first();

            if (!$filmPastingData) {
                $filmPastingData = BreaklotFilmpasting::where('furnace', $furnace)
                    ->where('mass_prod', $massProd)
                    ->where('layer', $layerOrdinal)
                    ->where('model', $model)
                    ->where('lot_no', $lotNo)
                    ->first();
            }

            $magProp = $reportData && $reportData->magnetic_property_data
                ? json_decode($reportData->magnetic_property_data, true)
                : [];

            $remarksRaw = $reportData?->note_reason_reject;
            $remarksDecoded = is_string($remarksRaw) ? json_decode($remarksRaw, true) : $remarksRaw;

            $batchesCount = TPMData::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer_no', $layerNumber)
                ->where('serial_no', $serial)
                ->count();

            $rowData = [
                'MPI_Date' => $tpmData[0]->date ?? '',
                'Pulse_Tracer_Machine' => $tpmData[0]->Tracer ?? '',
                'Furnace_Cycle_No' => $massProdData->cycle_no,
                'Mass_Production' => $massProdData->mass_prod,
                'Cycle_Pattern' => $massProdData->pattern_no,
                'Model' => $model,
                'Lot_No' => $lotNo,
                'Layer' => $layerOrdinal,
                'Batches' => $batchesCount,
                'Total_Lot_Qty' => $mapped['total_qty'],
                'Date' =>
                    !empty($filmPastingData->date)
                            ? $filmPastingData->date
                            : ($coating->date ?? ''),
                'M_C' =>
                    !empty($filmPastingData->machine_no)
                        ? $filmPastingData->machine_no
                        : ($coating->machine_no ?? ''),

                'Magnet_Weight' =>
                    !empty($filmPastingData->total_magnet_weight)
                        ? $filmPastingData->total_magnet_weight
                        : ($coating->total_magnet_weight ?? ''),
                'iHc_Target' => $magProp['ihcStandard'] ?? '',
                'iHc_Max' => $tpmAggregate && $tpmAggregate->maximum ? json_decode($tpmAggregate->maximum, true)['iHc'] ?? '' : '',
                'iHc_Min' => $tpmAggregate && $tpmAggregate->minimum ? json_decode($tpmAggregate->minimum, true)['iHc'] ?? '' : '',
                'iHc_Ave' => $tpmAggregate && $tpmAggregate->average ? json_decode($tpmAggregate->average, true)['iHc'] ?? '' : '',
                'Remarks' => (is_array($remarksDecoded) && count($remarksDecoded) > 0)
                    ? implode("\n", $remarksDecoded)
                    : (!empty($remarksDecoded) ? $remarksDecoded : 'NONE'),
                'Status' => !empty(trim($reportData->modified_smp_judgement ?? ''))
                    ? $reportData->modified_smp_judgement
                    : ($reportData->smp_judgement ?? ''),
                'HT_Trouble' => $massProdData->current_pattern === 'PASS' ? 'NO' : 'YES',
                'Special_Instruction' => $smpData->special_instruction ?? ''
            ];
            if ($formatType === 'Film Pasting') {
                $rowData = array_merge($rowData, [
                    'Film_Coating_Amount' => $filmPastingData->film_coating_amount ?? '',
                    'Film_Type' => $filmPastingData->film_type ?? '',
                    'Film_Class' => $filmPastingData->film_class ?? '',
                    'blank' => '',
                ]);
            } else {
                $rowData = array_merge($rowData, [
                    'Coating_Target' => $coating->min_tb_content ?? '',
                    'Coating_Max' => $coating->maximum ?? '',
                    'Coating_Min' => $coating->minimum ?? '',
                    'Coating_Ave' => $coating->average ?? '',
                ]);
            }

            $flattenedLayers[] = $rowData;
        }

        Log::info("Flattened layers count: " . count($flattenedLayers));

        Log::info("COATING DEBUG SNAPSHOT", [
            'furnace' => $furnace,
            'mass_prod' => $massProd,
            'coatings_loaded' => $coatings->map(function ($c) {
                return [
                    'layer' => $c->layer,
                    'min_tb_content' => $c->min_tb_content ?? null,
                    'max' => $c->maximum ?? null,
                    'min' => $c->minimum ?? null,
                ];
            })->values()->all(),
        ]);

        Log::info("LAYER COATING ASSIGNMENT CHECK", [
            'mapped_rows_layers' => collect($mappedRows)->pluck('layer')->values()->all(),
            'coating_keys_available' => $coatings->keys()->values()->all(),
        ]);

        return $flattenedLayers;
    }
}
