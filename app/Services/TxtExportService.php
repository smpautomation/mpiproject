<?php

namespace App\Services;

use App\Models\BreaklotInitialLotHt;
use App\Models\BreaklotCoating;
use App\Models\BreaklotSecondCoating;
use App\Models\TPMData; //Before: App\Models\TpmData
use App\Models\TPMDataCategory;
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

        $transformedFurnace = substr($furnace_no, 0, 1) . '-' . substr($furnace_no, 1);

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

                if (is_string($excess)) {
                    $layerData = json_decode($excess, true);
                } elseif (is_array($excess)) {
                    $layerData = $excess;
                } else {
                    $layerData = [];
                }
            }

            // --- MODEL_CODE RESOLUTION (layer aware) ---
            $layerForQuery = $layerKey == 10 ? 9.5 : $layerKey;
            Log::info("Layer {$layerKey} (query as {$layerForQuery}) for furnace {$transformedFurnace} mass_prod {$massPro}");

            $serialArray = array_unique(
                TPMData::where('mass_prod', $massPro)
                    ->where('furnace', $transformedFurnace)
                    ->where('layer_no', $layerForQuery)
                    ->pluck('serial_no')
                    ->toArray()
            );

            Log::info("Layer {$layerKey}: Serial numbers fetched: " . implode(',', $serialArray));

            $categoryRows = [];
            if (!empty($serialArray)) {
                $categoryRows = TPMDataCategory::whereIn('tpm_data_serial', $serialArray)
                    ->get(['actual_model', 'jhcurve_lotno', 'tpm_data_serial']);

                Log::info("Layer {$layerKey}: Category rows fetched count: " . $categoryRows->count());
            }

            // Detect boxes dynamically from JSON keys
            $boxes = [];
            if (!empty($layerData)) {
                foreach ($layerData as $item) {
                    $boxes = array_unique(array_merge($boxes, array_keys($item['data'] ?? [])));
                }
            }
            $allBoxes = array_unique(array_merge($allBoxes, $boxes));

            // Step 5: Build per-box rows
            foreach ($boxes as $area) {

                $rowData = [
                    'MODEL_NAME' => '0',
                    'COATING_MC_NO' => '0',
                    'LOT_NO' => '0',
                    'MC_NO' => '0',
                    'QTY' => '0',
                    'COATING' => '0',
                    'WT' => '0',
                    'BOX_NO' => '0',
                    'MODEL_CODE' => '0000000',
                    'RAW_MATERIAL_CODE' => '0000000',
                ];

                // Fill with real data from JSON
                $rawModelName = '0';
                $rawLotNo     = '0';

                foreach ($layerData as $item) {
                    $title = $item['rowTitle'] ?? '';
                    $data = $item['data'][$area] ?? '0';

                    $normalizedTitle = strtolower(str_replace([' ', ':', '.', '/'], '', $title));

                    switch ($normalizedTitle) {
                        case 'model':
                            $cleanModel = $this->sanitizeModelName($data);

                            $rowData['MODEL_NAME'] = $cleanModel;
                            $rawModelName = $cleanModel; // IMPORTANT: keep matching consistent
                            break;

                        case 'coatingmcno':
                            $rowData['COATING_MC_NO'] = $this->normalizeCoatingMcNo($data);
                            break;

                        case 'ltno':
                            $rowData['LOT_NO'] = $this->normalizeLotNo($data); // normalized for display
                            $rawLotNo = $data; // raw for serial lookup
                            $rowData['MC_NO']  = $this->extractMcNo($data);
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
                            $cleanBoxNo = str_replace(' ', '', $data);
                            $rowData['BOX_NO'] = str_pad($cleanBoxNo ?: '0', 11, '0', STR_PAD_LEFT);
                            break;

                        case 'rawmaterialcode':
                            $rowData['RAW_MATERIAL_CODE'] = $data;
                            break;
                    }
                }

                // --- Resolve correct serial via RAW MODEL + LOT ---
                $matchedSerial = null;

                foreach ($categoryRows as $cat) {
                    if (
                        $cat->actual_model == $rawModelName &&
                        $cat->jhcurve_lotno == $rawLotNo
                    ) {
                        $matchedSerial = $cat->tpm_data_serial;
                        Log::info("Layer {$layerKey}, Box {$area}: Matched MODEL_NAME {$rawModelName} + LOT_NO {$rawLotNo} => Serial {$matchedSerial}");
                        break;
                    }
                }

                if (!$matchedSerial) {
                    Log::info("Layer {$layerKey}, Box {$area}: No matching serial found for MODEL_NAME {$rawModelName} + LOT_NO {$rawLotNo}");
                }

                if ($matchedSerial) {
                    $tpmRow = TPMData::where('serial_no', $matchedSerial)->first();
                    $rowData['MODEL_CODE'] = $tpmRow->code_no ?? '0';
                    Log::info("Layer {$layerKey}, Box {$area}: MODEL_CODE resolved as {$rowData['MODEL_CODE']} from serial {$matchedSerial}");
                }

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

        //dd($lines->toArray());

        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data1.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "";
    }

    protected function sanitizeModelName(?string $value): string
    {
        if (!$value) return '0';

        // Trim + normalize spaces
        $value = preg_replace('/\s+/', ' ', trim($value));

        // Remove "2ND GBDP" and anything after it
        $value = preg_replace('/\s*2ND\s*GBDP.*$/i', '', $value);

        // Remove REPROCESS / RE-PROCESS (any case, both variants)
        $value = preg_replace('/\bRE[-\s]?PROCESS\b/i', '', $value);

        // Remove parentheses (both open and close, and anything inside already remains untouched)
        $value = str_replace(['(', ')'], '', $value);

        // Normalize spaces again after removals
        $value = preg_replace('/\s+/', ' ', trim($value));

        return trim($value);
    }

    private function normalizeCoatingMcNo($value)
    {
        if (!$value) return '0';

        $value = strtoupper(trim($value));

        if (str_starts_with($value, 'FP')) {
            $value = 'F' . substr($value, 2);
        }

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
        $formattedFurnace = preg_replace('/([A-Z]+)(\d+)/', '$1-$2', $furnace_no);

        // Fetch all TPM data for this furnace + mass production
        $tpmData = TPMData::where('furnace', 'LIKE', "{$formattedFurnace}%")
            ->where('mass_prod', $massPro)
            ->get();

        if ($tpmData->isEmpty()) {
            return 'No TPM data found for this furnace and mass production.';
        }

        $normalizedFurnace = $this->normalizeFurnaceCode($furnace_no);

        // Fetch MassProduction record
        $massProd = MassProduction::where('furnace', $normalizedFurnace)
            ->where('mass_prod', $massPro)
            ->first();

        if (!$massProd) {
            return 'No MassProduction data found.';
        }

        // Step 1: Determine layers and unique key pairs
        $layerKeys = ['T', 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $layerKeyPairs = [];

        foreach ($layerKeys as $layerKey) {
            $layerColumn = $layerKey === 'T' ? 'layer_9_5' : "layer_{$layerKey}";
            $layerJson = $massProd->$layerColumn ?? null;
            $layerData = $layerJson ? json_decode($layerJson, true) : [];

            if (empty($layerData)) {
                $layerKeyPairs[$layerKey] = [];
                continue;
            }

            // Extract MODEL + LOT NO pairs
            $modelRow = collect($layerData)->firstWhere('rowTitle', 'MODEL:');
            $lotRow   = collect($layerData)->firstWhere('rowTitle', 'LT. No.:');

            $pairs = [];
            foreach (range('A', 'K') as $letter) {
                $model = $modelRow['data'][$letter] ?? null;
                $lotNo = $lotRow['data'][$letter] ?? null;
                if ($model && $lotNo) {
                    $pairs[] = ['model' => $model, 'lot_no' => $lotNo];
                }
            }

            // Keep only unique pairs
            $layerKeyPairs[$layerKey] = array_values(array_unique($pairs, SORT_REGULAR));
        }

        $outputRows = [];

        foreach ($layerKeys as $layerKey) {
            $pairs = $layerKeyPairs[$layerKey] ?: [[]]; // preserve layer even if no pairs

            foreach ($pairs as $pair) {
                $model = $pair['model'] ?? null;
                $lotNo = $pair['lot_no'] ?? null;

                // Determine if normal layer or breaklot
                $isBreaklot = $model && $lotNo;

                // --- Step 2: Fetch TPM row and ReportData ---
                if (!$isBreaklot) {
                    // Normal single-key layer → fetch serial from massProd
                    $serialColumn = $layerKey === 'T' ? 'layer_9_5_serial' : "layer_{$layerKey}_serial";
                    $layerSerial = $massProd->$serialColumn ?? null;

                    $tpmRow = $layerSerial
                        ? TPMData::where('serial_no', $layerSerial)->first()
                        : null;

                    $reportData = $layerSerial
                        ? ReportData::where('tpm_data_serial', $layerSerial)->first()
                        : null;
                } else {
                    // Breaklot → fetch serial via TPMDataCategory
                    $serials = TPMDataCategory::where('actual_model', $model)
                        ->where('jhcurve_lotno', $lotNo)
                        ->pluck('tpm_data_serial');

                    $tpmRow = null;
                    $reportData = null;

                    foreach ($serials as $serial) {
                        $match = TPMData::where('serial_no', $serial)
                            ->where('furnace', $normalizedFurnace)
                            ->where('mass_prod', $massPro)
                            ->first();

                        if ($match) {
                            $tpmRow = $match;
                            $reportData = ReportData::where('tpm_data_serial', $serial)->first();
                            break;
                        }
                    }
                }

                // --- Step 3: Fetch Coating ---
                $layerNo = $layerKey === 'T' ? 9.5 : $layerKey;
                $coating = null;

                // Determine if this layer is a breaklot (multiple unique key pairs)
                $isBreaklotLayer = count($layerKeyPairs[$layerKey] ?? []) > 1;

                // Check if current pair is the initial lot (applies to both normal & breaklot)
                $initialLotRecord = BreaklotInitialLotHt::where('mass_prod', $massPro)
                    ->where('furnace', $normalizedFurnace)
                    ->where('layer', (string)$layerKey)
                    ->first();

                $isInitialLot = false;
                if ($initialLotRecord) {
                    if (($pair['model'] ?? null) === $initialLotRecord->initial_model &&
                        ($pair['lot_no'] ?? null) === $initialLotRecord->initial_lot) {
                        $isInitialLot = true;
                    }
                }

                // Determine coating source
                if (!$isBreaklotLayer || $isInitialLot) {
                    // Normal layer or initial lot → Coating first, fallback, then GbdpSecondCoating
                    $coating = Coating::where('furnace', $normalizedFurnace)
                        ->where('mass_prod', $massPro)
                        ->where('layer', (string)$layerNo)
                        ->first();

                    if (!$coating) {
                        $gbdp = GbdpSecondCoating::where('furnace', $normalizedFurnace)
                            ->where('mass_prod', $massPro)
                            ->where('layer', (string)$layerNo)
                            ->first();

                        if ($gbdp) {
                            $coating = is_array($gbdp->coating_info_2ndgbdp)
                                ? $gbdp->coating_info_2ndgbdp
                                : json_decode($gbdp->coating_info_2ndgbdp, true);
                        }
                    }
                } else {
                    // Breaklot additional pair → BreaklotCoating first, then BreaklotSecondCoating
                    $coating = BreaklotCoating::where('furnace', $normalizedFurnace)
                        ->where('mass_prod', $massPro)
                        ->where('layer', (string)$layerKey)
                        ->where('model', $pair['model'])
                        ->where('lot_no', $pair['lot_no'])
                        ->first();

                    if (!$coating) {
                        $blSecond = BreaklotSecondCoating::where('furnace', $normalizedFurnace)
                            ->where('mass_prod', $massPro)
                            ->where('layer', (string)$layerKey)
                            ->where('model', $pair['model'])
                            ->where('lot_no', $pair['lot_no'])
                            ->first();

                        if ($blSecond) {
                            $coating = is_array($blSecond->coating_info_2ndgbdp)
                                ? $blSecond->coating_info_2ndgbdp
                                : json_decode($blSecond->coating_info_2ndgbdp, true);
                        }
                    }
                }

                // --- Step 4: Extract Raw Material & Total Qty (precise per pair) ---
                $layerJsonData = $massProd->{$layerKey === 'T' ? 'layer_9_5' : "layer_{$layerKey}"} ?? null;
                $massLayerData = $layerJsonData ? json_decode($layerJsonData, true) : [];

                [$rawMaterialCode, $totalQty] = $this->extractRawAndQty(
                    $massLayerData,
                    $model,
                    $lotNo
                );

                // --- Step 5: Push row ---
                if ($tpmRow || $coating) {
                    $outputRows[] = [
                        $layerKey,
                        $tpmRow->code_no ?? '',
                        $tpmRow->raw_material_code ?? $rawMaterialCode,
                        $totalQty,
                        $coating['date'] ?? $coating?->date ?? '',
                        $coating['machine_no'] ?? $coating?->machine_no
                            ? str_replace('-', '0', preg_replace('/^FP/i', 'F', $coating['machine_no'] ?? $coating?->machine_no))
                            : '',
                        $coating['min_tb_content'] ?? $coating?->min_tb_content ?? 0,
                        $coating['total_magnet_weight'] ?? $coating?->total_magnet_weight ?? 0,
                        $coating['maximum'] ?? $coating?->maximum ?? 0,
                        $coating['minimum'] ?? $coating?->minimum ?? 0,
                        $coating['average'] ?? $coating?->average ?? 0,
                        $massProd?->furnace ? str_replace('-', '0', $massProd->furnace) : '',
                        $massProd?->cycle_no
                            ? ltrim(substr($massProd->cycle_no, strpos($massProd->cycle_no, '-') + 1), ' ')
                            : '',
                        $massProd?->batch_cycle_no ? preg_replace('/\D+/', '', $massProd->batch_cycle_no) : '',
                        $massProd?->pattern_no ?? '',
                        $massProd?->date_start ?? '',
                        $massProd?->date_finished ?? '',
                        $reportData?->length ?? 0,
                        $reportData?->width ?? 0,
                        $reportData?->thickness ?? 0,
                        $reportData?->material_grade ?? ''
                    ];
                } else {
                    // Layer exists but empty
                    $outputRows[] = [
                        $layerKey,
                        '0000000','0000000',0,'','','',0,0,0,0,0,'','','','','',0,0,0,''
                    ];
                }
            }
        }

        // --- Headers ---
        $header = [
            'LAYER','MODEL_CODE','RAW_MATERIAL_CODE','TOTAL_QUANTITY','COATING_DATE','COATING_MC_NO',
            'MIN_TB_CONTENT','TOTAL_MAGNET_WEIGHT','COATING_MAX','COATING_MIN','COATING_AVE',
            'FURNACE_MC_NO','CYCLE_NO','BATCH_CYCLE_NO','PATTERN','DATE_START','DATE_FINISH',
            'LENGTH','WIDTH','THICKNESS','MATERIAL_GRADE'
        ];

        $lines = collect($outputRows)
            ->map(fn($row) => implode(',', array_map([$this, 'convertToString'], $row)))
            ->prepend(implode(',', $header));

        //dd($lines->toArray()); // verify output

        // --- Save ---
        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data2.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "";
    }

    /**
     * Extract RAW MATERIAL CODE and TOTAL QTY for a given pair index
     */
    protected function extractRawAndQty(array $massLayerData, ?string $model = null, ?string $lotNo = null): array
    {
        $rawMaterialCode = '0';
        $totalQty = 0;

        if (empty($massLayerData)) return [$rawMaterialCode, $totalQty];

        $modelRow = collect($massLayerData)->firstWhere('rowTitle', 'MODEL:');
        $lotRow   = collect($massLayerData)->firstWhere('rowTitle', 'LT. No.:');

        if (!$modelRow || !$lotRow) return [$rawMaterialCode, $totalQty];

        // Normalize inputs
        $model = $model ? trim(strtoupper($model)) : null;
        $lotNo = $lotNo ? trim($lotNo) : null;

        $columnLetter = null;

        foreach ($modelRow['data'] as $letter => $modelValue) {
            $m = $modelValue ? trim(strtoupper($modelValue)) : null;
            $l = isset($lotRow['data'][$letter]) ? trim($lotRow['data'][$letter]) : null;

            if ($m === $model && $l === $lotNo) {
                $columnLetter = $letter;
                break;
            }
        }

        // Controlled fallback
        if (!$columnLetter) {
            // take first non-empty column instead of blindly 'A'
            foreach ($modelRow['data'] as $letter => $val) {
                if (!empty($val)) {
                    $columnLetter = $letter;
                    break;
                }
            }
        }

        if (!$columnLetter) return [$rawMaterialCode, $totalQty];

        foreach ($massLayerData as $item) {
            $row = strtolower($item['rowTitle'] ?? '');
            $value = $item['data'][$columnLetter] ?? null;

            if (str_contains($row, 'raw material code')) {
                $rawMaterialCode = $value ?? '0';
            } elseif (str_contains($row, 'total qty')) {
                $totalQty = $value ?? 0;
            }
        }

        return [$rawMaterialCode, $totalQty];
    }

    private function normalizeFurnaceCode($furnace)
    {
        // K40 -> K-40
        if (preg_match('/^([A-Z])(\d+)$/', $furnace, $m)) {
            return $m[1] . '-' . $m[2];
        }

        return $furnace; // already normalized or unknown format
    }

    private function getSecondCoatingFallbackByKeyPair($furnace, $massProd, $layerNo, $model, $lotNo)
    {
        $row = BreaklotSecondCoating::where('furnace', $furnace)
            ->where('mass_prod', $massProd)
            ->where('layer', $layerNo)
            ->where('model', $model)
            ->where('lot_no', $lotNo)
            ->first();

        if (!$row) {
            //dump("[2ND COATING] Row NOT FOUND for layer {$layerNo}, model {$model}, lot {$lotNo}");
            return null;
        }

        $data = $row->coating_info_2ndgbdp ?? null;

        if (empty($data)) {
            //dump("[2ND COATING] coating_info_2ndgbdp IS EMPTY for layer {$layerNo}, model {$model}, lot {$lotNo}");
            return null;
        }

        // Decode JSON if it's a string
        if (is_string($data)) {
            $data = json_decode($data, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                //dump("[2ND COATING] JSON decode failed for layer {$layerNo}, model {$model}, lot {$lotNo}", json_last_error_msg(), $data);
                return null;
            }
        }

        // If it's a list (array with numeric keys), unwrap the first element
        if (is_array($data) && array_is_list($data)) {
            $data = $data[0] ?? null;
        }

        if (!is_array($data)) {
            //dump("[2ND COATING] Data is NOT array after normalization for layer {$layerNo}, model {$model}, lot {$lotNo}", $data);
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

        //dump("[2ND COATING] Normalized data for layer {$layerNo}, model {$model}, lot {$lotNo}", $normalized);

        return (object) $normalized;
    }



    public function exportData3(string $furnace_no, string $massPro)
    {
        $formattedFurnace = preg_replace('/([A-Z]+)(\d+)/', '$1-$2', $furnace_no);
        //dump($formattedFurnace);

        // Fetch TPM data by mass_prod and furnace only
        $tpmData = TPMData::with('remark', 'category')
            ->where('furnace', 'LIKE', "{$formattedFurnace}")
            ->where('mass_prod', $massPro)
            ->get();

        if ($tpmData->isEmpty()) {
            return 'No data found.';
        }

        // --- hd5 support ---
        $massProdData = MassProduction::where('mass_prod', $massPro)
            ->where('furnace', $formattedFurnace)
            ->first();

        if (!$massProdData) {
            return 'Mass production data not found.';
        }

        $cycleNo = $massProdData->cycle_no ?? '0';

        // Group data by layer_no
        $groupedByLayer = $tpmData->groupBy(fn($item) => (string) $item->layer_no);

        /*
        |--------------------------------------------------------------------------
        | EXPORT SCHEMA — THIS DEFINES THE FILE FORMAT
        |--------------------------------------------------------------------------
        */
        $schema = [

            // ---- FIXED FRONT ----
            'LAYER' => fn($item, $ctx) => $ctx['layer'],
            'DATE_INSP'     => fn($item) => $item->date ?? '0',
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
                ? str_replace(
                    '-',
                    '0',
                    preg_replace('/^FP/i', 'F', $item->furnace_no)
                )
                : '0',
            'ZONE' => fn($item) => $item->zone ?? '0',
            'PASS_NO' => function ($item) use ($massProdData) {
                $passNo = (int) ($item->pass_no ?? 0);

                if (
                    isset($massProdData->cycle_pattern) && strtoupper($massProdData->cycle_pattern) === 'ABNORMAL'
                    || isset($massProdData->current_pattern) && strtoupper($massProdData->current_pattern) === 'ABNORMAL'
                ) {
                    $passNo += 100;
                }

                return $passNo;
            },
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

        $layerOrder = ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'T'];

        foreach ($layerOrder as $layer) {
            // Determine the numeric value for queries
            $layerFilter = $layer === 'T' ? '9.5' : (string) $layer;

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
                    $value = str_replace(["\r", "\n"], [' ', ' '], $value);
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
        $layers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 9.5];

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
