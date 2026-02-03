<?php

namespace App\Services;

use App\Models\MassProduction;
use App\Models\TPMData;
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
            return []; // stop early if no record
        }

        $attributes = collect($massProdData->getAttributes());

        // Extract layers
        $layersData = $attributes
            ->filter(fn($value, $key) => preg_match('/^layer_\d+(_\d+)?$/', $key) && $value !== null)
            ->mapWithKeys(fn($value, $key) => [$key => @json_decode($value, true) ?: $value]);

        $layerSerials = $attributes
            ->filter(fn($value, $key) => preg_match('/^layer_\d+(_\d+)?_serial$/', $key) && $value !== null);

        // Preload related data
        $tpmRecords = TPMData::whereIn('serial_no', $layerSerials)->get()->groupBy('serial_no');
        $tpmAggregates = TPMDataAggregateFunctions::whereIn('tpm_data_serial', $layerSerials)->get()->keyBy('tpm_data_serial');
        $reportRecords = ReportData::whereIn('tpm_data_serial', $layerSerials)->get()->keyBy('tpm_data_serial');
        $coatings = Coating::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->get()
            ->keyBy(fn($c) => $c->layer);

        $flattenedLayers = [];

        foreach ($layersData as $layerCol => $layerArray) {
            $serial = $massProdData->{$layerCol . '_serial'} ?? null;
            $formatType = $massProdData->{$layerCol . '_format_type'} ?? null;

            $layerNumber = (float) str_replace('_', '.', str_replace('layer_', '', $layerCol));
            $layerOrdinal = (string)$layerNumber;

            $coating = $coatings[$layerNumber] ?? null;
            $tpmData = $serial && isset($tpmRecords[$serial]) ? $tpmRecords[$serial]->values() : collect();
            $tpmAggregate = $serial && isset($tpmAggregates[$serial]) ? $tpmAggregates[$serial] : null;
            $reportData = $serial && isset($reportRecords[$serial]) ? $reportRecords[$serial] : null;

            $smpData = SmpData::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer', $layerOrdinal)
                ->first();

            $filmPastingData = FilmPastingData::where('furnace', $furnace)
                ->where('mass_prod', $massProd)
                ->where('layer', $layerOrdinal)
                ->first();

            $getRowData = fn($title) => collect($layerArray)->firstWhere('rowTitle', $title)['data'] ?? '';
            $magProp = $reportData && $reportData->magnetic_property_data
                ? json_decode($reportData->magnetic_property_data, true)
                : [];

            $rowData = [
                'MPI_Date' => $reportData->date_emailed ?? '',
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
                'iHc_Target' => $magProp['ihcStandard'] ?? '',
                'iHc_Max' => $tpmAggregate && $tpmAggregate->maximum ? json_decode($tpmAggregate->maximum, true)['iHc'] ?? '' : '',
                'iHc_Min' => $tpmAggregate && $tpmAggregate->minimum ? json_decode($tpmAggregate->minimum, true)['iHc'] ?? '' : '',
                'iHc_Ave' => $tpmAggregate && $tpmAggregate->average ? json_decode($tpmAggregate->average, true)['iHc'] ?? '' : '',
                'Remarks' => $smpData->remarks ?? '',
                'Status' => $reportData->smp_judgement ?? '',
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

            $flattenedLayers[$layerCol] = $rowData;
        }

        Log::info("Flattened layers count: " . count($flattenedLayers));

        return $flattenedLayers;
    }
}
