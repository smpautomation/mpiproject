<?php

namespace App\Services;

use App\Models\TPMData; //Before: App\Models\TpmData
use App\Models\MassProduction;
use App\Models\ReportData;
use App\Models\Coating;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TxtExportService
{
    public function exportData1(string $furnace_no, string $massPro)
    {
        // Step 1: Get the latest date for the specified furnace + mass_prod
        $dateToGet = TPMData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('mass_prod', $massPro)
            ->orderBy('date', 'desc')
            ->value('date');

        if (!$dateToGet) {
            return 'No date found for this furnace and mass production.';
        }

        // Step 2: Fetch the mass production row
        $massProdData = MassProduction::where('mass_prod', $massPro)->first();

        if (!$massProdData) {
            return 'Mass production data not found.';
        }

        // Step 3: Prepare layer/area structure
        $layers = [1,2,3,4,5,6,7,8,9,9.5]; // include 9.5 as final layer

        $outputRows = [];
        $allBoxes = []; // will collect all boxes across layers

        foreach ($layers as $layerKey) {
            // Handle 9.5 column naming
            $layerColumn = 'layer_' . str_replace('.', '_', $layerKey);
            $serialColumn = 'layer_' . str_replace('.', '_', $layerKey) . '_serial';

            $layerData = json_decode($massProdData->$layerColumn, true);
            $layerSerial = $massProdData->$serialColumn;

            // Get model code from TPM data using the serial number
            $tpmRow = TPMData::where('serial_no', $layerSerial)->first();
            $modelCode = $tpmRow->code_no ?? '0';

            // Detect boxes dynamically from JSON keys
            $boxes = [];
            if (!empty($layerData)) {
                foreach ($layerData as $item) {
                    $boxes = array_unique(array_merge($boxes, array_keys($item['data'] ?? [])));
                }
            }

            // Merge with global boxes array
            $allBoxes = array_unique(array_merge($allBoxes, $boxes));

            // Step 5: Build per-box rows
            foreach ($boxes as $area) {
                $rowData = [
                    'MODEL_NAME' => '0',
                    'COATING_MC_NO' => '0',
                    'LOT_NO' => '0',
                    'QTY' => '0',
                    'COATING' => '0',
                    'WT' => '0',
                    'BOX_NO' => '0',
                    'MODEL_CODE' => $modelCode,
                    'RAW_MATERIAL_CODE' => '0',
                ];

                // Fill with real data from JSON
                foreach ($layerData as $item) {
                    $title = $item['rowTitle'] ?? '';
                    $data = $item['data'][$area] ?? '0';

                    $normalizedTitle = strtolower(str_replace([' ', ':', '.', '/'], '', $title));

                    switch ($normalizedTitle) {
                        case 'model':
                            $rowData['MODEL_NAME'] = $data;
                            break;
                        case 'coatingmcno':
                            $rowData['COATING_MC_NO'] = $data;
                            break;
                        case 'ltno':
                            $rowData['LOT_NO'] = $data;
                            break;
                        case 'qty(pcs)':
                            $rowData['QTY'] = $data;
                            break;
                        case 'coating':
                            $rowData['COATING'] = $data;
                            break;
                        case 'wt(kg)':
                            $rowData['WT'] = $data;
                            break;
                        case 'boxno':
                            $rowData['BOX_NO'] = $data;
                            break;
                        case 'rawmaterialcode':
                            $rowData['RAW_MATERIAL_CODE'] = $data;
                            break;
                    }
                }

                //dd($layerKey, $area, $rowData); // commented out
                $outputRows[$layerKey][$area] = $rowData;
            }
        }

        // Step 6: Flatten rows for export, converting layer 10 to 'T'
        $finalRows = [];
        $layerOrder = range(1, 10);
        rsort($layerOrder);

        foreach ($layerOrder as $layer) {
            $outputLayer = $layer === 10 ? 'T' : (string)$layer;

            foreach ($allBoxes as $area) {
                $row = data_get($outputRows, "{$layer}.{$area}", [
                    'MODEL_NAME' => 0,
                    'COATING_MC_NO' => 0,
                    'LOT_NO' => 0,
                    'QTY' => 0,
                    'COATING' => 0,
                    'WT' => 0,
                    'BOX_NO' => 0,
                    'MODEL_CODE' => 0,
                    'RAW_MATERIAL_CODE' => 0,
                ]);

                $finalRows[] = array_merge(['LAYER' => $outputLayer, 'AREA' => $area], $row);
            }
        }

        // Step 7: Format into lines and save
        $header = "LAYER,AREA,MODEL_NAME,COATING_MC_NO,LOT_NO,QTY,COATING,WT,BOX_NO,MODEL_CODE,RAW_MATERIAL_CODE";
        $lines = collect($finalRows)->map(fn($row) => implode(',', $row))->prepend($header);

        //dd($lines->toArray()); // commented out

        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data1.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "";
    }


    public function exportData2(string $furnace_no, string $massPro)
    {
        // Step 1: Get the latest TPM date for this furnace + mass prod
        $dateToGet = TpmData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('mass_prod', $massPro)
            ->orderBy('date', 'desc')
            ->value('date');

        if (!$dateToGet) {
            return 'No TPM data found for this furnace and mass production.';
        }

        // Step 2: Fetch all TPM data for that date and furnace
        $tpmData = TpmData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('mass_prod', $massPro)
            ->where('date', $dateToGet)
            ->get();

        if ($tpmData->isEmpty()) {
            return 'No TPM data found.';
        }

        // Step 3: Fetch MassProduction record
        $massProd = MassProduction::where('mass_prod', $massPro)->first();
        if (!$massProd) {
            return 'No MassProduction data found.';
        }

        // Step 4: Define layers (T first, then 9 → 1)
        $layerKeys = ['T', 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $outputRows = [];

        foreach ($layerKeys as $layerKey) {
            // Handle 9.5 → T
            $layerColumn = $layerKey === 'T' ? 'layer_9_5' : "layer_{$layerKey}";
            $serialColumn = $layerKey === 'T' ? 'layer_9_5_serial' : "layer_{$layerKey}_serial";

            // Fetch the layer JSON + serial from mass production
            $layerJson = $massProd->$layerColumn ?? null;
            $layerSerial = $massProd->$serialColumn ?? null;

            $massLayerData = $layerJson ? json_decode($layerJson, true) : [];

            // Fetch TPM + Report + Coating data using the serial
            $tpmRow = $layerSerial ? TpmData::where('serial_no', $layerSerial)->first() : null;
            $reportData = $layerSerial ? ReportData::where('tpm_data_serial', $layerSerial)->first() : null;
            $coating = $layerSerial ? Coating::where('mass_prod', $massPro)->where('layer', $layerKey === 'T' ? 9.5 : $layerKey)->first() : null;

            // dd($layerKey, $layerSerial, $tpmRow, $reportData, $coating);

            // Extract model, material, qty from JSON
            $modelCodeValue = '0';
            $rawMaterialCode = '0';
            $totalQty = 0;

            foreach ($massLayerData as $item) {
                $title = strtolower($item['rowTitle'] ?? '');
                $value = $item['data']['A'] ?? null; // Simplified: only first column or adjust as needed

                if (str_contains($title, 'model')) {
                    $modelCodeValue = $value ?? '0';
                } elseif (str_contains($title, 'raw material')) {
                    $rawMaterialCode = $value ?? '0';
                } elseif (str_contains($title, 'total qty') || str_contains($title, 'qty')) {
                    $totalQty = $value ?? 0;
                }
            }

            if ($layerSerial && $tpmRow) {
                $outputRows[] = [
                    $layerKey,
                    $tpmRow->code_no ?? $modelCodeValue,
                    $tpmRow->raw_material_code ?? $rawMaterialCode,
                    $totalQty,
                    $coating?->date ?? '',
                    $coating?->machine_no ?? '',
                    $coating?->min_tb_content ?? 0,
                    $coating?->total_magnet_weight ?? 0,
                    $coating?->maximum ?? 0,
                    $coating?->minimum ?? 0,
                    $coating?->average ?? 0,
                    $massProd?->furnace ?? '',
                    $massProd?->cycle_no ?? '',
                    $massProd?->batch_cycle_no ?? '',
                    $massProd?->pattern_no ?? '',
                    $massProd?->date_start ?? '',
                    $massProd?->date_finished ?? '',
                    $reportData?->length ?? 0,
                    $reportData?->width ?? 0,
                    $reportData?->thickness ?? 0,
                    $reportData?->material_grade ?? ''
                ];
            } else {
                // Completely blank — only layer preserved
                $outputRows[] = [
                    $layerKey,
                    '0','0',0,'','',0,0,0,0,0,
                    '','','','','','',0,0,0,''
                ];
            }
        }

        // Step 5: Define headers (non-negotiable)
        $header = [
            'LAYER', 'MODEL_CODE', 'RAW_MATERIAL_CODE', 'TOTAL_QUANTITY',
            'COATING_DATE', 'COATING_MACHINE_NO', 'MIN_TB_CONTENT', 'TOTAL_MAGNET_WEIGHT',
            'COATING_MAX', 'COATING_MIN', 'COATING_AVE',
            'FURNACE_MACHINE_NO', 'CYCLE_NO', 'BATCH_CYCLE_NO', 'PATTERN',
            'DATE_START', 'DATE_FINISH',
            'LENGTH', 'WIDTH', 'THICKNESS', 'MATERIAL GRADE'
        ];

        // Step 6: Prepare lines
        $lines = collect($outputRows)
            ->map(fn($row) => implode(',', array_map([$this, 'convertToString'], $row)))
            ->prepend(implode(',', $header));

        //dd($lines->toArray()); // You can comment this after verification

        // Step 7: Save to .txt
        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data2.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "";
    }

    public function exportData3(string $furnace_no, string $massPro)
    {
        // Step 1: Get the latest date
        $dateToGet = TpmData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->orderBy('date', 'desc')
            ->value('date');

        if (!$dateToGet) {
            return 'No data found.';
        }

        // Clean furnace_no (remove hyphen if any)
        $furnace_no = str_replace('-', '', $furnace_no);

        // Step 2: Fetch TPM data with relations and mass_prod filter
        $tpmData = TpmData::with('remark', 'category')
            ->where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('date', $dateToGet)
            ->where('mass_prod', $massPro)
            ->get();

        if ($tpmData->isEmpty()) {
            return 'No data found.';
        }

        $firstItem = $tpmData->first();

        // Excluded fields from base attributes
        $excludeFields = [
            'id','created_at','updated_at','serial_no','x','y','furnace_id',
            'temperature','data_status','order_no','Density','HRX','MRX','HRY','MRY',
            'IHKA','MRA','IHKB','MRB','IHKC','MRC','HR','HRO'
        ];

        $baseAttributes = collect($firstItem->getAttributes())
            ->except($excludeFields)
            ->keys();

        $remarkAttributes = $firstItem->remark
            ? collect($firstItem->remark->getAttributes())
                ->except(['id','created_at','updated_at'])
                ->keys()
            : collect();

        $fixedFront = [
            'layer_no','lot_no','date','code_no','type','press_1','press_2',
            'machine_no','sintering_furnace_no','furnace_no','zone','pass_no','hd5'
        ];

        $customOrder = [
            'Br','Br_remarks','iHc','iHc_remarks','iHk','iHk_remarks',
            'BHMax','BHMax_remarks','iHr95','iHr95_remarks','iHr98','iHr98_remarks',
            'iHkiHc','iHkiHc_remarks','Br4pai','Br4pai_remarks','bHc','bHc_remarks',
            'Squareness','Squareness_remarks','4paiId','4paiId_remarks',
            '4paiIs','4paiIs_remarks','4paiIa','4paiIa_remarks','Tracer'
        ];

        $allKeys = collect($baseAttributes)
            ->map(fn($key) => [$key, "{$key}_remarks"])
            ->flatten()
            ->merge($remarkAttributes)
            ->unique()
            ->values()
            ->all();

        $headersFixed = $fixedFront;
        $headersCustom = array_intersect($customOrder, $allKeys);
        $headers = array_merge($headersFixed, $headersCustom);

        // Group data by layer_no
        $groupedByLayer = $tpmData->groupBy(fn($item) => (float) $item->layer_no);

        // --- hd5 support: load MassProduction for layer_serial lookup ---
        $massProdData = MassProduction::where('mass_prod', $massPro)->first();
        if (!$massProdData) {
            return 'Mass production data not found.';
        }

        $lines = [];
        $lines[] = implode(',', $headers); // Header

        $layerOrder = ['1','2','3','4','5','6','7','8','9','T'];

        foreach ($layerOrder as $layer) {
            $layerFilter = $layer === 'T' ? 9.5 : (float)$layer;
            // --- hd5 lookup for this layer ---
            $serialColumn = 'layer_' . str_replace('.', '_', $layerFilter) . '_serial';
            $layerSerial = $massProdData->$serialColumn ?? null;

            $hd5Value = '0';
            if ($layerSerial) {
                $bhInfo = ReportData::where('tpm_data_serial', $layerSerial)->value('data_bh_info');
                if ($bhInfo) {
                    $decodedBh = json_decode($bhInfo, true);
                    $hd5Value = $decodedBh['data'] ?? '0';
                }
            }
            $rowsForLayer = $groupedByLayer[$layerFilter] ?? collect();

            if ($rowsForLayer->isEmpty()) {
                // Default row for missing layer
                $defaultRow = [];
                foreach ($headers as $header) {
                    if ($header === 'layer_no') {
                        $defaultRow[] = $layer;
                    } else {
                        $defaultRow[] = '0';
                    }
                }
                $lines[] = implode(',', $defaultRow);
                continue;
            }

            foreach ($rowsForLayer as $item) {
                $remark = $item->remark;
                $row = [];

                $row[] = $layer; // Layer_no as string including T

                $lot_no = trim(($item->press_1 ?? '') . ' ' . ($item->press_2 ?? '') . ' ' . ($item->machine_no ?? ''));
                $row[] = $lot_no;

                foreach ($headers as $column) {
                    if (in_array($column, ['layer_no','lot_no'])) continue;

                    // hd5 must come from ReportData->data_bh_info (already fetched into $hd5Value)
                    if ($column === 'hd5') {
                        $row[] = $this->convertToString($hd5Value);
                        continue;
                    }

                    if (isset($item->$column)) {
                        $row[] = $this->convertToString($item->$column ?? '0');
                    } elseif ($remark && property_exists($remark, $column)) {
                        $row[] = $this->convertToString($remark->$column ?? '0');
                    } else {
                        $row[] = '0';
                    }
                }

                // Escape values
                $escapedRow = array_map(function($value) {
                    $value = $this->convertToString($value);
                    $value = str_replace(["\r","\n"], [' ',' '], $value);
                    return str_contains($value, ',') ? "\"$value\"" : $value;
                }, $row);

                $lines[] = implode(',', $escapedRow);
            }
        }

        //dd($lines);

        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data3.txt";
        File::put($filePath, implode("\n", $lines));

        return "";
    }

    public function exportData4(string $furnace_no, string $massPro)
    {
        // Step 1: Get the latest date for the specified furnace + mass_prod
        $dateToGet = TPMData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('mass_prod', $massPro)
            ->orderBy('date', 'desc')
            ->value('date');

        if (!$dateToGet) {
            return 'No date found for this furnace and mass production.';
        }

        // Step 2: Fetch the mass production row
        $massProdData = MassProduction::where('mass_prod', $massPro)->first();

        if (!$massProdData) {
            return 'Mass production data not found.';
        }

        // Step 3: Define layers
        $layers = [1,2,3,4,5,6,7,8,9,9.5];

        $outputRows = [];
        $hasValidData = false;

        foreach ($layers as $layerKey) {
            $serialColumn = 'layer_' . str_replace('.', '_', $layerKey) . '_serial';
            $layerSerial = $massProdData->$serialColumn ?? null;

            $report = $layerSerial
                ? ReportData::where('tpm_data_serial', $layerSerial)->first()
                : null;

            $info = $report ? json_decode($report->data_VT_info, true) : [];

            // Determine if this layer has real VT data
            $isValidVT = is_array($info) && (
                (!empty($info['iHc']) && $info['iHc'] != 0) ||
                (!empty($info['temp']) && $info['temp'] != 0) ||
                (!empty($info['sample']) && count($info['sample']) > 0) ||
                (!empty($info['iHcResult']) && count($info['iHcResult']) > 0)
            );

            if ($isValidVT) {
                $hasValidData = true;
            }

            $outputRows[] = [
                'LAYER'           => $layerKey == 9.5 ? 'T' : $layerKey,
                'IHC'             => $info['iHc'] ?? 0,
                'TEMP'            => $info['temp'] ?? 0,
                'SAMPLES'         => isset($info['sample']) ? implode(',', $info['sample']) : '',
                'IHC_RESULTS'     => isset($info['iHcResult']) ? implode(',', $info['iHcResult']) : '',
                'SAMPLE_QTY'      => $info['sample_qty'] ?? 0,
                'REMARKS'         => $info['remarks'] ?? '',
                'SAMPLE_REMARKS'  => isset($info['sample_remarks']) ? implode(',', $info['sample_remarks']) : '',
            ];
        }

        if (!$hasValidData) {
            return 'No VT data found for any layer — export aborted.';
        }

        // Step 5: Format into lines and save
        $header = "LAYER,IHC,TEMP,SAMPLES,IHC_RESULTS,SAMPLE_QTY,REMARKS,SAMPLE_REMARKS";
        $lines = collect($outputRows)->map(fn($row) => implode(',', $row))->prepend($header);
        //dd($lines->toArray());
        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data4.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "";
    }


    private function convertToString($value)
    {
        if (is_array($value)) {
            return json_encode($value); // If it's an array, convert to JSON string
        }

        if (is_object($value)) {
            return method_exists($value, '__toString') ? $value->__toString() : json_encode($value); // Handle objects
        }

        return (string)$value; // Fallback for strings, numbers, and null
    }

}
