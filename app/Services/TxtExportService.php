<?php

namespace App\Services;

use App\Models\TPMData; //Before: App\Models\TpmData
use App\Models\MassProduction;
use App\Models\ExcessLayers;
use App\Models\ReportData;
use App\Models\Coating;
use App\Models\GbdpSecondCoating;
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
        $layers = range(1, 10);

        $outputRows = [];
        $allBoxes = []; // will collect all boxes across layers

        foreach ($layers as $layerKey) {
            // Handle 9.5 column naming
            if ($layerKey == 10) {
                $layerColumn = 'layer_9_5';
                $serialColumn = 'layer_9_5_serial';
            } else {
                $layerColumn = 'layer_' . $layerKey;
                $serialColumn = 'layer_' . $layerKey . '_serial';
            }

            $layerData = json_decode($massProdData->$layerColumn, true);
            $layerSerial = $massProdData->$serialColumn;

            if (empty($layerData)) {
                $excess = ExcessLayers::where('furnace', preg_replace('/([A-Z]+)(\d+)/', '$1-$2', $furnace_no))
                       ->where('mass_prod', $massPro)
                       ->where('layer', $layerKey)
                       ->value('layer_data');
                //dump($layerKey, $excess);
                $layerData = $excess ?? [];
            }

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
                    'MODEL_NAME'      => '0',
                    'COATING_MC_NO'   => '0',
                    'LOT_NO'          => '0',
                    'MC_NO'           => '0',
                    'QTY'             => '0',
                    'COATING'         => '0',
                    'WT'              => '0',
                    'BOX_NO'          => '0',
                    'MODEL_CODE'      => $modelCode,
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
                            $rowData['COATING_MC_NO'] = $this->normalizeCoatingMcNo($data);
                            break;
                        case 'ltno':
                            $rowData['LOT_NO'] = $this->normalizeLotNo($data);
                            $rowData['MC_NO']  = $this->extractMcNo($data); // <-- new column right after LOT_NO
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
                    'MC_NO' => 0,
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
        $header = "LAYER,AREA,MODEL_NAME,COATING_MC_NO,LOT_NO,MC_NO,QTY,COATING,WT,BOX_NO,MODEL_CODE,RAW_MATERIAL_CODE";
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

    private function normalizeCoatingMcNo($value)
    {
        if (!$value) return '0';

        $value = strtoupper(trim($value));

        // Match: letters + optional dash + numbers
        if (preg_match('/^([A-Z]+)-?(\d+)$/', $value, $m)) {
            $prefix = $m[1];
            $number = str_pad($m[2], 3, '0', STR_PAD_LEFT);

            return $prefix . $number;
        }

        // Fallback: just remove dash
        return str_replace('-', '', $value);
    }

    private function extractMcNo($value)
    {
        if (!$value) return '0';

        // Grab everything after the hyphen, if exists
        if (str_contains($value, '-')) {
            [$before, $after] = explode('-', $value, 2);

            // Keep only digits from the hyphen part
            $mc = preg_replace('/\D/', '', $after);

            return $mc !== '' ? $mc : '0';
        }

        return '0';
    }

    private function normalizeLotNo($value)
    {
        if (!$value) return '0';

        $value = trim($value);

        // Remove everything after dash: 421/543-3 -> 421/543
        $value = preg_replace('/-.*/', '', $value);

        // If no slash, just return what's left
        if (!str_contains($value, '/')) {
            return $value;
        }

        [$a, $b] = explode('/', $value, 2);

        // Keep only digits
        $a = preg_replace('/\D/', '', $a);
        $b = preg_replace('/\D/', '', $b);

        if ($a === '' || $b === '') {
            return $a !== '' ? $a : ($b !== '' ? $b : '0');
        }

        return ((int)$a <= (int)$b) ? $a : $b;
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

        $normalizedFurnace = $this->normalizeFurnaceCode($furnace_no);

        // Step 3: Fetch MassProduction record
        $massProd = MassProduction::where('furnace', $normalizedFurnace)
            ->where('mass_prod', $massPro)
            ->first();
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

            $layerNo = $layerKey === 'T' ? 9.5 : $layerKey;

            $layerNoStr = (string) $layerNo; // force string

            Log::info('[COATING] Primary lookup start', [
                'furnace' => $furnace_no,
                'mass_prod' => $massPro,
                'layer' => $layerNoStr,
            ]);

            $coating = Coating::where('furnace', $normalizedFurnace)
                ->where('mass_prod', $massPro)
                ->where('layer', $layerNoStr)
                ->first();

            if (!$coating) {
                Log::warning('[COATING] Primary lookup MISS. Trying second coating fallback...', [
                    'normalized_furnace' => $normalizedFurnace,
                    'mass_prod' => $massPro,
                    'layer' => $layerNoStr,
                ]);

                $coating = $this->getSecondCoatingFallback($normalizedFurnace, $massPro, $layerNoStr);

                if ($coating) {
                    Log::info('[COATING] Fallback HIT', [
                        'source' => 'gbdp_second_coating',
                    ]);
                } else {
                    Log::error('[COATING] Fallback MISS. No coating data found at all.', [
                        'furnace' => $normalizedFurnace,
                        'mass_prod' => $massPro,
                        'layer' => $layerNoStr,
                    ]);
                }
            }



            //dd($layerKey, $layerSerial, $tpmRow, $reportData, $coating);

            // Extract model, material, qty from JSON
            $rawMaterialCode = '0';
            $totalQty = 0;

            foreach ($massLayerData as $item) {
                $title = strtolower($item['rowTitle'] ?? '');
                $value = $item['data']['A'] ?? null;
                if (empty($value)) {
                    $value = $item['data']['B'] ?? null;
                }

                if (str_contains($title, 'raw material')) {
                    $rawMaterialCode = $value ?? '0';
                } elseif (str_contains($title, 'total qty')) {
                    $totalQty = $value ?? 0;
                }
            }

            if ($tpmRow || $coating) {
                $outputRows[] = [
                    $layerKey,
                    $tpmRow->code_no ?? '',
                    $tpmRow->raw_material_code ?? $rawMaterialCode,
                    $totalQty,
                    $coating?->date ?? '',
                    $coating?->machine_no
                        ? str_replace('-', '0', $coating->machine_no)
                        : '',
                    $coating?->min_tb_content ?? 0,
                    $coating?->total_magnet_weight ?? 0,
                    $coating?->maximum ?? 0,
                    $coating?->minimum ?? 0,
                    $coating?->average ?? 0,
                    $massProd?->furnace
                        ? str_replace('-', '0', $massProd->furnace)
                        : '',
                    $massProd?->cycle_no
                        ? ltrim(substr($massProd->cycle_no, strpos($massProd->cycle_no, '-') + 1), ' ')
                        : '',
                    $massProd?->batch_cycle_no
                        ? preg_replace('/\D+/', '', $massProd->batch_cycle_no)
                        : '',
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
            'COATING_DATE', 'COATING_MC_NO', 'MIN_TB_CONTENT', 'TOTAL_MAGNET_WEIGHT',
            'COATING_MAX', 'COATING_MIN', 'COATING_AVE',
            'FURNACE_MC_NO', 'CYCLE_NO', 'BATCH_CYCLE_NO', 'PATTERN',
            'DATE_START', 'DATE_FINISH',
            'LENGTH', 'WIDTH', 'THICKNESS', 'MATERIAL_GRADE'
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

    private function normalizeFurnaceCode($furnace)
    {
        // K40 -> K-40
        if (preg_match('/^([A-Z])(\d+)$/', $furnace, $m)) {
            return $m[1] . '-' . $m[2];
        }

        return $furnace; // already normalized or unknown format
    }

    private function getSecondCoatingFallback($furnace, $massProd, $layerNo)
    {
        Log::info('[2ND COATING] Lookup start', [
            'furnace' => $furnace,
            'mass_prod' => $massProd,
            'layer' => (string)$layerNo,
        ]);

        $row = GbdpSecondCoating::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->where('layer', (string)$layerNo)
            ->first();

        if (!$row) {
            Log::warning('[2ND COATING] Row NOT FOUND', [
                'furnace' => $furnace,
                'mass_prod' => $massProd,
                'layer' => (string)$layerNo,
            ]);
            return null;
        }

        Log::info('[2ND COATING] Row FOUND', [
            'id' => $row->id ?? null,
        ]);

        Log::info('[2ND COATING] Raw coating_info_2ndgbdp', [
            'type' => gettype($row->coating_info_2ndgbdp),
            'value' => $row->coating_info_2ndgbdp,
        ]);

        if (empty($row->coating_info_2ndgbdp)) {
            Log::error('[2ND COATING] coating_info_2ndgbdp IS EMPTY');
            return null;
        }

        $data = $row->coating_info_2ndgbdp;

        if (is_array($data) && array_is_list($data)) {
            Log::warning('[2ND COATING] Data is list, unwrapping first element');
            $data = $data[0] ?? null;
        }

        Log::info('[2ND COATING] Normalized data', [
            'type' => gettype($data),
            'value' => $data,
        ]);

        if (!is_array($data)) {
            Log::error('[2ND COATING] Data is NOT array after normalize');
            return null;
        }

        $normalized = [
            'date' => $data['date'] ?? null,
            'machine_no' => $data['machine_no'] ?? null,
            'min_tb_content' => $data['min_tb_content'] ?? null,
            'total_magnet_weight' => $data['total_magnet_weight'] ?? null,
            'maximum' => $data['maximum'] ?? null,
            'minimum' => $data['minimum'] ?? null,
            'average' => $data['average'] ?? null,
        ];

        Log::info('[2ND COATING] Final mapped object', $normalized);

        return (object) $normalized;
    }


    public function exportData3(string $furnace_no, string $massPro)
    {
        // Step 1: Get the latest date
        $dateToGet = TpmData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('mass_prod', $massPro)
            ->orderBy('date', 'desc')
            ->value('date');

        if (!$dateToGet) {
            return 'No data found.';
        }

        // Clean furnace_no
        $furnace_no = str_replace('-', '', $furnace_no);

        // Step 2: Fetch TPM data
        $tpmData = TpmData::with('remark', 'category')
            ->where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('date', $dateToGet)
            ->where('mass_prod', $massPro)
            ->get();

        if ($tpmData->isEmpty()) {
            return 'No data found.';
        }

        // --- hd5 support ---
        $formattedFurnace = preg_replace('/([A-Z]+)(\d+)/', '$1-$2', $furnace_no);

        $massProdData = MassProduction::where('mass_prod', $massPro)
            ->where('furnace', $formattedFurnace)
            ->first();

        if (!$massProdData) {
            return 'Mass production data not found.';
        }

        $cycleNo = $massProdData->cycle_no ?? '0';

        // Group data by layer_no
        $groupedByLayer = $tpmData->groupBy(fn($item) => trim((string)$item->layer_no));

        /*
        |--------------------------------------------------------------------------
        | EXPORT SCHEMA — THIS DEFINES THE FILE FORMAT
        |--------------------------------------------------------------------------
        */
        $schema = [

            // ---- FIXED FRONT ----
            'LAYER' => fn($item, $ctx) => $ctx['layer'],
            'DATE'     => fn($item) => $item->date ?? '0',
            'MODEL_CODE'  => fn($item) => $item->code_no ?? '0',
            'MATERIAL_GRADE'     => fn($item) => $item->type ?? '0',
            'LOT_NO'  => fn($item) => $item->press_1 ?? '0',
            'MC_NO' => fn($item) => $item->machine_no ?? '0',
            'FURNACE_MC_NO' => fn($item) =>
                $item->sintering_furnace_no
                    ? substr(explode('-', $item->sintering_furnace_no)[0], 0, 1)
                    . '0'
                    . substr(explode('-', $item->sintering_furnace_no)[0], 1)
                    : '0',
            'CYCLE_NO' => fn($item) => ltrim(explode('-', $cycleNo)[1] ?? '0', '0'),
            'COATING_MC_NO' => fn($item) =>
                $item->furnace_no
                    ? str_replace('-', '0', $item->furnace_no)
                    : '0',
            'ZONE' => fn($item) => $item->zone ?? '0',
            'PASS_NO' => fn($item) => $item->pass_no ?? '0',
            'HD5' => fn($item, $ctx) => $ctx['hd5'] ?? '0',

            // ---- CUSTOM ORDER ----
            'BR' => fn($item) => $item->Br ?? '0',
            'BR_REMARKS' => fn($item) => $item->remark->Br_remarks ?? '0',

            'IHC' => fn($item) => $item->iHc ?? '0',
            'IHC_REMARKS' => fn($item) => $item->remark->iHc_remarks ?? '0',

            'IHK' => fn($item) => $item->iHk ?? '0',
            'IHK_REMARKS' => fn($item) => $item->remark->iHk_remarks ?? '0',

            'BHMAX' => fn($item) => $item->BHMax ?? '0',
            'BHMAX_REMARKS' => fn($item) => $item->remark->BHMax_remarks ?? '0',

            'IHR95' => fn($item) => $item->iHr95 ?? '0',
            'IHR95_REMARKS' => fn($item) => $item->remark->iHr95_remarks ?? '0',

            'IHR98' => fn($item) => $item->iHr98 ?? '0',
            'IHR98_REMARKS' => fn($item) => $item->remark->iHr98_remarks ?? '0',

            'IHKIHC' => fn($item) => $item->iHkiHc ?? '0',
            'IHKIHC_REMARKS' => fn($item) => $item->remark->iHkiHc_remarks ?? '0',

            'BR4PAI' => fn($item) => $item->Br4pai ?? '0',
            'BR4PAI_REMARKS' => fn($item) => $item->remark->Br4pai_remarks ?? '0',

            'BHC' => fn($item) => $item->bHc ?? '0',
            'BHC_REMARKS' => fn($item) => $item->remark->bHc_remarks ?? '0',

            'SQUARENESS' => fn($item) => $item->Squareness ?? '0',
            'SQUARENESS_REMARKS' => fn($item) => $item->remark->Squareness_remarks ?? '0',

            '4PAIID' => fn($item) => $item->{'4paiId'} ?? '0',
            '4PAIID_REMARKS' => fn($item) => $item->remark->{'4paiId_remarks'} ?? '0',

            '4PAIIS' => fn($item) => $item->{'4paiIs'} ?? '0',
            '4PAIIS_REMARKS' => fn($item) => $item->remark->{'4paiIs_remarks'} ?? '0',

            '4PAIIA' => fn($item) => $item->{'4paiIa'} ?? '0',
            '4PAIIA_REMARKS' => fn($item) => $item->remark->{'4paiIa_remarks'} ?? '0',

            'TRACER' => fn($item) => $item->Tracer ?? '0',
        ];

        $headers = array_keys($schema);

        $lines = [];
        $lines[] = implode(',', $headers);

        $layerOrder = ['1','2','3','4','5','6','7','8','9','T'];

        foreach ($layerOrder as $layer) {
            // Determine the numeric value for queries
            $layerFilter = $layer === 'T' ? '9.5' : $layer;

            // Determine the display value
            $layerDisplay = $layer === 'T' ? 'T' : $layer;

            // --- hd5 lookup ---
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
                $row = [];
                foreach ($schema as $header => $resolver) {
                    $row[] = $header === 'layer_no' ? $layerDisplay : '0';
                }
                $lines[] = implode(',', $row);
                continue;
            }

            foreach ($rowsForLayer as $item) {
                $context = [
                    'layer' => $layerDisplay,
                    'hd5' => $hd5Value,
                ];

                $row = [];

                foreach ($schema as $resolver) {
                    $value = $resolver($item, $context);
                    $value = $this->convertToString($value);
                    $value = str_replace(["\r","\n"], [' ',' '], $value);
                    $row[] = str_contains($value, ',') ? "\"$value\"" : $value;
                }

                $lines[] = implode(',', $row);
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
            File::makeDirectory($directory, 0755, recursive: true);
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
