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
use App\Models\GbdpSecondHeatTreatment;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class TxtExportService
{
    public function exportData1(string $furnace_no, string $massPro)
    {
        $dateToGet = TPMData::where('sintering_furnace_no', 'LIKE', "{$furnace_no}-%")
            ->where('mass_prod', $massPro)
            ->orderBy('date', 'desc')
            ->value('date');

        if (!$dateToGet) {
            return 'No date found for this furnace and mass production.';
        }

        $transformedFurnace = substr($furnace_no, 0, 1) . '-' . substr($furnace_no, 1);

        $massProdData = MassProduction::where('mass_prod', $massPro)
            ->where('furnace', $transformedFurnace)
            ->first();

        if (!$massProdData) {
            return 'Mass production data not found.';
        }

        $serialArray = TPMData::where('mass_prod', $massPro)
            ->where('furnace', $transformedFurnace)
            ->pluck('serial_no')
            ->unique()
            ->toArray();

        $categoryRows = TPMDataCategory::whereIn('tpm_data_serial', $serialArray)
            ->get(['actual_model', 'jhcurve_lotno', 'tpm_data_serial']);

        $lotModelCodeCache = [];

        $layers = ['1','2','3','4','5','6','7','8','9','9.5'];

        $outputRows = [];
        $allBoxes = [];

        foreach ($layers as $layerKey) {

            $layerColumn = $layerKey === '9.5'
                ? 'layer_9_5'
                : 'layer_' . $layerKey;

            $massProdRaw = $massProdData->{$layerColumn} ?? null;

            if ($layerKey === '9.5') {
                Log::info('[9.5 DEBUG STEP 1 RAW FETCH]', [
                    'layerColumn' => $layerColumn,
                    'is_null' => $massProdRaw === null,
                    'type' => gettype($massProdRaw),
                    'sample_raw' => $massProdRaw,
                ]);
            }

            $massProdDataArray = is_array($massProdRaw)
                ? $massProdRaw
                : json_decode($massProdRaw, true);

            if ($layerKey === '9.5') {
                Log::info('[9.5 DEBUG STEP 2 AFTER DECODE]', [
                    'decoded_is_array' => is_array($massProdDataArray),
                    'row_count' => is_array($massProdDataArray) ? count($massProdDataArray) : 0,
                    'first_row' => $massProdDataArray[0] ?? null,
                ]);
            }

            $massProdDataArray = is_array($massProdDataArray) ? $massProdDataArray : [];

            // -------------------------
            // 9.5 RAW STRUCTURE DEBUG
            // -------------------------
            if ($layerKey === '9.5') {
                Log::info('[9.5 STEP 0 RAW KEYS]', [
                    'keys' => collect($massProdDataArray)
                        ->flatMap(fn($r) => array_keys($r['data'] ?? []))
                        ->unique()
                        ->values()
                        ->toArray()
                ]);
            }

            $excess = ExcessLayers::where([
                'mass_prod' => $massPro,
                'furnace'   => $transformedFurnace,
                'layer'     => $layerKey,
            ])->first();

            $excessDataArray = [];

            if ($excess && !empty($excess->layer_data)) {
                $decoded = is_array($excess->layer_data)
                    ? $excess->layer_data
                    : json_decode($excess->layer_data, true);

                $excessDataArray = is_array($decoded) ? $decoded : [];
            }

            // -------------------------
            // MERGE
            // -------------------------
            $mergedLayerData = $massProdDataArray;
            if ($layerKey === '9.5') {
                Log::info('[9.5 DEBUG STEP 3 BEFORE SLOT DETECTION]', [
                    'merged_count' => count($mergedLayerData),
                    'available_titles' => array_map(fn($r) => $r['rowTitle'] ?? null, $mergedLayerData),
                ]);
            }

            foreach ($excessDataArray as $excessRow) {

                $rowTitle = $excessRow['rowTitle'] ?? null;
                if (!$rowTitle) continue;

                $matched = false;

                foreach ($mergedLayerData as &$mainRow) {
                    if (($mainRow['rowTitle'] ?? null) === $rowTitle) {
                        $mainRow['data'] = array_merge(
                            $mainRow['data'] ?? [],
                            $excessRow['data'] ?? []
                        );
                        $matched = true;
                        break;
                    }
                }
                unset($mainRow);

                if (!$matched) {
                    $mergedLayerData[] = $excessRow;
                }
            }

            // -------------------------
            // SLOT DETECTION
            // -------------------------
            if ($layerKey === '9.5') {

                $boxes = $this->detectSlotGroup($mergedLayerData);

                // safety fallback (prevents downstream break)
                if (empty($boxes)) {
                    Log::warning('[9.5 SLOT DETECTION FAILED]', [
                        'fallback_used' => true,
                        'reason' => 'No exact match for group1 or group2',
                    ]);

                    // optional fallback: assume group2 as safest default
                    $boxes = ['B','D','F','H','K'];
                }

                Log::info('[9.5 DETECTED SLOT GROUP]', [
                    'boxes' => $boxes
                ]);

            } else {

                $boxes = [];

                foreach ($mergedLayerData as $item) {
                    $boxes = array_merge($boxes, array_keys($item['data'] ?? []));
                }

                $boxes = array_values(array_unique($boxes));
            }

            if ($layerKey !== '9.5') {
                $allBoxes = array_values(array_unique(array_merge($allBoxes, $boxes)));
            }

            if ($layerKey === '9.5') {
                Log::info('[9.5 DEBUG STEP 4 FINAL BOXES]', [
                    'boxes' => $boxes,
                    'box_count' => count($boxes),
                ]);
            }

            // -------------------------
            // BUILD ROWS
            // -------------------------

             if ($layerKey === '9.5') {

                $outputRows['9.5'] = $this->buildLayer9_5Rows(
                    $mergedLayerData,
                    $categoryRows->all(),
                    $lotModelCodeCache
                );

                continue;
            }

            foreach ($boxes as $area) {

                $rowData = [
                    'MODEL_NAME'        => '0',
                    'COATING_MC_NO'     => '0',
                    'LOT_NO'            => '0',
                    'MC_NO'             => '0',
                    'QTY'               => '0',
                    'COATING'           => '0',
                    'WT'                => '0',
                    'BOX_NO'            => '0',
                    'MODEL_CODE'        => '0',
                    'RAW_MATERIAL_CODE' => '0',
                ];

                $rawModelFull = null;
                $rawLot = null;

                foreach ($mergedLayerData as $item) {

                    $title = strtolower(str_replace([' ', ':', '.', '/'], '', $item['rowTitle'] ?? ''));
                    $value = $item['data'][$area] ?? '0';

                    // -------------------------
                    // 9.5 SLOT VALUE TRACE
                    // -------------------------
                    if ($layerKey === '9.5' && $area === 'A') {
                        Log::info('[9.5 VALUE TRACE]', [
                            'rowTitle' => $item['rowTitle'] ?? null,
                            'value' => $value
                        ]);
                    }

                    switch ($title) {

                        case 'model':
                            $rawModelFull = $value;
                            $rowData['MODEL_NAME'] = $this->sanitizeModelName($value);
                            break;

                        case 'coatingmcno':
                            $rowData['COATING_MC_NO'] = $this->normalizeCoatingMcNo($value);
                            break;

                        case 'ltno':
                            $rowData['LOT_NO'] = $this->normalizeLotNo($value);
                            $rawLot = $value;
                            $rowData['MC_NO'] = $this->extractMcNo($value);
                            break;

                        case 'qty(pcs)':
                            $rowData['QTY'] = $value;
                            break;

                        case 'coating':
                            $rowData['COATING'] = $value;
                            break;

                        case 'wt(kg)':
                            $rowData['WT'] = $value;
                            break;

                        case 'boxno':
                            $clean = str_replace(' ', '', $value);
                            $rowData['BOX_NO'] = str_pad($clean ?: '0', 11, '0', STR_PAD_LEFT);
                            break;

                        case 'rawmaterialcode':
                            $rowData['RAW_MATERIAL_CODE'] = $value;
                            break;
                    }
                }

                // -------------------------
                // MODEL CODE RESOLUTION DEBUG
                // -------------------------
                $lotKey = trim($rawModelFull ?? '') . '|' . trim($rawLot ?? '');

                if (!isset($lotModelCodeCache[$lotKey])) {
                    $matchedSerial = null;

                    foreach ($categoryRows as $cat) {
                        if (
                            $cat->actual_model === $rawModelFull &&
                            $cat->jhcurve_lotno === $rawLot
                        ) {
                            $matchedSerial = $cat->tpm_data_serial;


                            break;
                        }
                    }

                    if (!$matchedSerial) {

                        $lotModelCodeCache[$lotKey] = '0';

                    } else {

                        $tpm = TPMData::where('serial_no', $matchedSerial)->first();

                        $lotModelCodeCache[$lotKey] = $tpm->code_no ?? '0';
                    }
                }

                $rowData['MODEL_CODE'] = $lotModelCodeCache[$lotKey];

                $outputRows[$layerKey][$area] = $rowData;
            }
        }

        // -------------------------
        // FLATTEN (UNCHANGED)
        // -------------------------
        $finalRows = [];

        $layerOrder = ['9.5','9','8','7','6','5','4','3','2','1'];

        foreach ($layerOrder as $layer) {

            if ($layer === '9.5') {

                $fixedAreas = ['A','B','C','D','E','F','G','H','J','K'];

                foreach ($fixedAreas as $area) {

                    $row = $outputRows['9.5'][$area] ?? [
                        'MODEL_NAME'=>0,'COATING_MC_NO'=>0,'LOT_NO'=>0,'MC_NO'=>0,
                        'QTY'=>0,'COATING'=>0,'WT'=>0,'BOX_NO'=>0,
                        'MODEL_CODE'=>0,'RAW_MATERIAL_CODE'=>0,
                    ];

                    $finalRows[] = array_merge([
                        'LAYER' => 'T',
                        'AREA'  => $area
                    ], $row);
                }

                continue;
            }

            foreach ($allBoxes as $area) {
                $row = data_get($outputRows, "{$layer}.{$area}", [
                    'MODEL_NAME'=>0,'COATING_MC_NO'=>0,'LOT_NO'=>0,'MC_NO'=>0,
                    'QTY'=>0,'COATING'=>0,'WT'=>0,'BOX_NO'=>0,
                    'MODEL_CODE'=>0,'RAW_MATERIAL_CODE'=>0,
                ]);

                $finalRows[] = array_merge([
                    'LAYER' => (string)$layer,
                    'AREA'  => $area
                ], $row);
            }
        }

        $header = "LAYER,AREA,MODEL_NAME,COATING_MC_NO,LOT_NO,MC_NO,QTY,COATING,WT,BOX_NO,MODEL_CODE,RAW_MATERIAL_CODE";

        $lines = collect($finalRows)
            ->map(fn($row) => implode(',', $row))
            ->prepend($header);

        //dd($lines->toArray());

        $directory = public_path("files/{$furnace_no} {$massPro}");

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put("{$directory}/Data1.txt", implode("\n", $lines->toArray()));

        return "";
    }

    //dd($lines->toArray());

    private function detectSlotGroup(array $layerData): array
    {
        $allKeys = [];

        foreach ($layerData as $row) {
            $allKeys = array_merge($allKeys, array_keys($row['data'] ?? []));
        }

        $allKeys = array_values(array_unique($allKeys));
        sort($allKeys);

        $group1 = ['A','C','E','G','J'];
        $group2 = ['B','D','F','H','K'];

        sort($group1);
        sort($group2);

        if ($allKeys === $group1) {
            return $group1;
        }

        if ($allKeys === $group2) {
            return $group2;
        }

        // fallback safety net
        return [];
    }

    private function buildLayer9_5Rows(
        array $layerData,
        array $categoryRows,
        array &$lotModelCodeCache
    ): array
    {
        $output = [];

        $boxes = $this->detectSlotGroup($layerData);

        foreach ($boxes as $area) {

            $rowData = [
                'MODEL_NAME'        => '0',
                'COATING_MC_NO'     => '0',
                'LOT_NO'            => '0',
                'MC_NO'             => '0',
                'QTY'               => '0',
                'COATING'           => '0',
                'WT'                => '0',
                'BOX_NO'            => '0',
                'MODEL_CODE'        => '0',
                'RAW_MATERIAL_CODE' => '0',
            ];

            $rawModelFull = null;
            $rawLot = null;

            foreach ($layerData as $item) {

                $title = strtolower(str_replace([' ', ':', '.', '/'], '', $item['rowTitle'] ?? ''));
                $value = $item['data'][$area] ?? '0';

                Log::info('[9.5 METHOD TRACE]', [
                    'area' => $area,
                    'title' => $title,
                    'available_keys' => array_keys($item['data'] ?? []),
                    'resolved_value' => $value,
                ]);

                switch ($title) {

                    case 'model':
                        $rawModelFull = $value;
                        $rowData['MODEL_NAME'] = $this->sanitizeModelName($value);
                        break;

                    case 'coatingmcno':
                        $rowData['COATING_MC_NO'] = $this->normalizeCoatingMcNo($value);
                        break;

                    case 'ltno':
                        $rowData['LOT_NO'] = $this->normalizeLotNo($value);
                        $rawLot = $value;
                        $rowData['MC_NO'] = $this->extractMcNo($value);
                        break;

                    case 'qty(pcs)':
                        $rowData['QTY'] = $value;
                        break;

                    case 'coating':
                        $rowData['COATING'] = $value;
                        break;

                    case 'wt(kg)':
                        $rowData['WT'] = $value;
                        break;

                    case 'boxno':
                        $clean = str_replace(' ', '', $value);
                        $rowData['BOX_NO'] = str_pad($clean ?: '0', 11, '0', STR_PAD_LEFT);
                        break;

                    case 'rawmaterialcode':
                        $rowData['RAW_MATERIAL_CODE'] = $value;
                        break;
                }
            }

            $lotKey = trim($rawModelFull ?? '') . '|' . trim($rawLot ?? '');

            if (!isset($lotModelCodeCache[$lotKey])) {

                $matchedSerial = null;

                foreach ($categoryRows as $cat) {

                    if (
                        $cat->actual_model === $rawModelFull &&
                        $cat->jhcurve_lotno === $rawLot
                    ) {
                        $matchedSerial = $cat->tpm_data_serial;
                        break;
                    }
                }

                if (!$matchedSerial) {

                    $lotModelCodeCache[$lotKey] = '0';

                } else {

                    $tpm = TPMData::where('serial_no', $matchedSerial)->first();

                    $lotModelCodeCache[$lotKey] = $tpm->code_no ?? '0';
                }
            }

            $rowData['MODEL_CODE'] = $lotModelCodeCache[$lotKey];

            $output[$area] = $rowData;
        }

        return $output;
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

        // remove everything after dash: 421/543-3 -> 421/543
        $value = preg_replace('/-.*/', '', $value);

        // if slash exists, ALWAYS take LEFT side
        if (str_contains($value, '/')) {
            [$left] = explode('/', $value, 2);
            $left = preg_replace('/\D/', '', $left);
            return $left !== '' ? $left : '0';
        }

        // no slash case: just digits cleanup
        $value = preg_replace('/\D/', '', $value);

        return $value !== '' ? $value : '0';
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

        $has2ndgbdp = GbdpSecondHeatTreatment::where('furnace', $normalizedFurnace)
            ->where('mass_prod', $massPro)
            ->exists();

        if (!$massProd) {
            return 'No MassProduction data found.';
        }

        // Step 1: Determine layers and unique key pairs
        $layerKeys = ['T', 9, 8, 7, 6, 5, 4, 3, 2, 1];
        $layerKeyPairs = [];
        $pairMainLayers = [];

        foreach ($layerKeys as $layerKey) {
            $layerColumn = $layerKey === 'T' ? 'layer_9_5' : "layer_{$layerKey}";
            $layerJson = $massProd->$layerColumn ?? null;
            $layerData = $layerJson ? json_decode($layerJson, true) : [];

            /**
             * NEW: fallback to ExcessLayers if empty
             */
            if (empty($layerData)) {

                $excess = ExcessLayers::where('furnace', $normalizedFurnace)
                    ->where('mass_prod', $massPro)
                    ->where('layer', (string)$layerKey)
                    ->first();

                if (!$excess) {
                    $layerKeyPairs[$layerKey] = [];
                    continue;
                }

                $excessData = is_array($excess->layer_data)
                    ? $excess->layer_data
                    : json_decode($excess->layer_data, true);

                $modelRow = collect($excessData)->firstWhere('rowTitle', 'MODEL:');
                $lotRow   = collect($excessData)->firstWhere('rowTitle', 'LT. No.:');

                $pairs = [];

                foreach (range('A', 'K') as $letter) {

                    $model = $modelRow['data'][$letter] ?? null;
                    $lotNo = $lotRow['data'][$letter] ?? null;

                    if ($model && $lotNo) {

                        /**
                         * IMPORTANT:
                         * Find MAIN layer of this pair in MassProduction
                         */
                        $foundLayer = null;

                        foreach ($layerKeys as $searchLayerKey) {

                            $searchColumn = $searchLayerKey === 'T'
                                ? 'layer_9_5'
                                : "layer_{$searchLayerKey}";

                            $searchJson = $massProd->$searchColumn ?? null;
                            $searchData = $searchJson ? json_decode($searchJson, true) : [];

                            if (empty($searchData)) {
                                continue;
                            }

                            $searchModelRow = collect($searchData)->firstWhere('rowTitle', 'MODEL:');
                            $searchLotRow   = collect($searchData)->firstWhere('rowTitle', 'LT. No.:');

                            foreach (range('A', 'K') as $i) {
                                if (
                                    ($searchModelRow['data'][$i] ?? null) === $model &&
                                    ($searchLotRow['data'][$i] ?? null) === $lotNo
                                ) {
                                    $foundLayer = $searchLayerKey;
                                    break 2;
                                }
                            }
                        }

                        if ($foundLayer !== null) {

                            $key = $model . '|' . $lotNo;

                            if (!isset($seenPairs[$key])) {

                                $pairs[] = [
                                    'model' => $model,
                                    'lot_no' => $lotNo
                                ];

                                // IMPORTANT
                                // Store the MAIN layer where this pair belongs
                                $pairMainLayers[$layerKey][$key] = $foundLayer;

                                $seenPairs[$key] = true;
                            }
                        }
                    }
                }

                $layerKeyPairs[$layerKey] = $pairs;

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

            $excess = ExcessLayers::where('furnace', $normalizedFurnace)
                ->where('mass_prod', $massPro)
                ->where('layer', (string)$layerKey)
                ->first();

            if ($excess) {

                $excessData = is_array($excess->layer_data)
                    ? $excess->layer_data
                    : json_decode($excess->layer_data, true);

                $modelRow = collect($excessData)->firstWhere('rowTitle', 'MODEL:');
                $lotRow   = collect($excessData)->firstWhere('rowTitle', 'LT. No.:');

                foreach (range('A', 'K') as $letter) {

                    $model = $modelRow['data'][$letter] ?? null;
                    $lotNo = $lotRow['data'][$letter] ?? null;

                    if (!$model || !$lotNo) {
                        continue;
                    }

                    $key = $model . '|' . $lotNo;

                    $exists = collect($layerKeyPairs[$layerKey])
                        ->contains(fn ($pair) =>
                            $pair['model'] === $model &&
                            $pair['lot_no'] === $lotNo
                        );

                    if ($exists) {
                        continue;
                    }

                    $foundLayer = null;

                    foreach ($layerKeys as $searchLayerKey) {

                        $searchColumn = $searchLayerKey === 'T'
                            ? 'layer_9_5'
                            : "layer_{$searchLayerKey}";

                        $searchJson = $massProd->$searchColumn ?? null;
                        $searchData = $searchJson ? json_decode($searchJson, true) : [];

                        if (empty($searchData)) {
                            continue;
                        }

                        $searchModelRow = collect($searchData)->firstWhere('rowTitle', 'MODEL:');
                        $searchLotRow   = collect($searchData)->firstWhere('rowTitle', 'LT. No.:');

                        foreach (range('A', 'K') as $i) {

                            if (
                                ($searchModelRow['data'][$i] ?? null) === $model &&
                                ($searchLotRow['data'][$i] ?? null) === $lotNo
                            ) {
                                $foundLayer = $searchLayerKey;
                                break 2;
                            }
                        }
                    }

                    $layerKeyPairs[$layerKey][] = [
                        'model' => $model,
                        'lot_no' => $lotNo
                    ];

                    if ($foundLayer !== null) {
                        $pairMainLayers[$layerKey][$key] = $foundLayer;
                    }
                }
            }
        }

        $outputRows = [];

        foreach ($layerKeys as $layerKey) {
            $pairs = $layerKeyPairs[$layerKey] ?? [];

            if (empty($pairs)) {
                $pairs = [[
                    'model' => null,
                    'lot_no' => null
                ]];
            }

            foreach ($pairs as $pair) {

                $model = $pair['model'] ?? null;
                $lotNo = $pair['lot_no'] ?? null;

                $key = $model && $lotNo ? $model . '|' . $lotNo : null;

                // Determine if normal layer or breaklot
                $isBreaklot = $model && $lotNo;

                $effectiveLayer = $pairMainLayers[$layerKey][$key] ?? $layerKey;

                // --- Step 2: Fetch TPM row and ReportData ---
                if (!$isBreaklot) {
                    // Normal single-key layer → fetch serial from massProd
                    $serialColumn = $effectiveLayer === 'T'
                        ? 'layer_9_5_serial'
                        : "layer_{$effectiveLayer}_serial";
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

                /**
                 * IMPORTANT
                 *
                 * Normal layers:
                 *      use current layer
                 *
                 * ExcessLayers:
                 *      use MAIN layer where model+lot came from
                 */

                $coatingLookupLayer = $layerKey;

                $key = ($pair['model'] ?? '') . '|' . ($pair['lot_no'] ?? '');

                if (isset($pairMainLayers[$layerKey][$key])) {
                    $coatingLookupLayer = $pairMainLayers[$layerKey][$key];
                }

                $coatingLookupLayerNo =
                    $coatingLookupLayer === 'T'
                        ? 9.5
                        : $coatingLookupLayer;

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

                $isSecondGbdp = false;

                $coating = $this->resolveCoating(
                    $normalizedFurnace,
                    $massPro,
                    $isBreaklotLayer,
                    $isInitialLot,
                    $layerKey,
                    $coatingLookupLayerNo,
                    $model,
                    $lotNo,
                    $normalizedFurnace,
                    $isSecondGbdp
                );

                $coating1stGbdp = null;
                $heatTreatment1stGbdp = null;

                if ($isSecondGbdp) {
                    $coating1stGbdp = $coating['coating_info_1stgbdp'];
                    $coating = $coating['coating_info_2ndgbdp'];

                    $secondHeatTreatment = GbdpSecondHeatTreatment::where('furnace', $normalizedFurnace)
                        ->where('mass_prod', $massPro)
                        ->where('layer', (string)$layerKey)
                        ->first();

                    if ($secondHeatTreatment) {
                        $heatTreatment1stGbdp = is_array($secondHeatTreatment->gbdp_1st)
                            ? $secondHeatTreatment->gbdp_1st
                            : json_decode($secondHeatTreatment->gbdp_1st, true);

                        $heatTreatment2ndGbdp = is_array($secondHeatTreatment->gbdp_2nd)
                            ? $secondHeatTreatment->gbdp_2nd
                            : json_decode($secondHeatTreatment->gbdp_2nd, true);
                    }
                }

                // --- Step 4: Extract Raw Material & Total Qty (precise per pair) ---

                $rawQtyLookupLayer = $coatingLookupLayer;

                $rawQtyLayerColumn = $rawQtyLookupLayer === 'T'
                    ? 'layer_9_5'
                    : "layer_{$rawQtyLookupLayer}";

                $layerJsonData = $massProd->$rawQtyLayerColumn ?? null;

                $massLayerData = $layerJsonData
                    ? json_decode($layerJsonData, true)
                    : [];

                [$rawMaterialCode, $totalQty] = $this->extractRawAndQty(
                    $massLayerData,
                    $model,
                    $lotNo
                );

                // --- Step 5: Push row ---
                if ($tpmRow && $isSecondGbdp) {
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
                        substr($coating['time_start'] ?? $coating?->time_start ?? '', 0, 5),
                        substr($coating['time_finish'] ?? $coating?->time_finish ?? '', 0, 5),
                        $coating1stGbdp['date'] ?? '',
                        $coating1stGbdp['machine_no'] ? str_replace('-', '0', preg_replace('/^FP/i', 'F', $coating1stGbdp['machine_no'])) : '',
                        $coating1stGbdp['min_tb_content'] ?? 0,
                        $coating1stGbdp['total_magnet_weight'] ?? 0,
                        substr($coating1stGbdp['time_start'] ?? '', 0, 5),
                        substr($coating1stGbdp['time_finish'] ?? '', 0, 5),
                        $coating['maximum'] ?? $coating?->maximum ?? 0,
                        $coating['minimum'] ?? $coating?->minimum ?? 0,
                        $coating['average'] ?? $coating?->average ?? 0,
                        $coating1stGbdp['maximum'] ?? 0,
                        $coating1stGbdp['minimum'] ?? 0,
                        $coating1stGbdp['average'] ?? 0,
                        $heatTreatment1stGbdp['furnace_machine'] ? str_replace('-', '0', $heatTreatment1stGbdp['furnace_machine']) : '',
                        $heatTreatment1stGbdp['cycle_no']
                            ? ltrim(substr($heatTreatment1stGbdp['cycle_no'], strpos($heatTreatment1stGbdp['cycle_no'], '-') + 1), ' ')
                            : '',
                        $heatTreatment1stGbdp['batch_cycle_no'] ? preg_replace('/\D+/', '', $heatTreatment1stGbdp['batch_cycle_no']) : '',
                        $heatTreatment1stGbdp['pattern_no'] ?? '',
                        $heatTreatment1stGbdp['date_start'] ?? '',
                        $heatTreatment1stGbdp['date_finished'] ?? '',
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
                        $reportData?->material_grade ?? '',
                        (int) preg_replace('/\D.*/', '', $reportData?->mpi_sample_quantity ?? ''),
                    ];
                } else if($tpmRow && $coating){
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
                        substr($coating['time_start'] ?? $coating?->time_start ?? '', 0, 5),
                        substr($coating['time_finish'] ?? $coating?->time_finish ?? '', 0, 5),
                        '',
                        '',
                        '',
                        '',
                        '',
                        '',
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
                        $reportData?->material_grade ?? '',
                        (int) preg_replace('/\D.*/', '', $reportData?->mpi_sample_quantity ?? ''),
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
            'MIN_TB_CONTENT','TOTAL_MAGNET_WEIGHT','COATING_DATE_START','COATING_DATE_FINISH','COATING_MAX','COATING_MIN','COATING_AVE',
            'FURNACE_MC_NO','CYCLE_NO','BATCH_CYCLE_NO','PATTERN','DATE_START','DATE_FINISH',
            'LENGTH','WIDTH','THICKNESS','MATERIAL_GRADE'
        ];

        $header2ndgbdp = [
            'LAYER','MODEL_CODE','RAW_MATERIAL_CODE','TOTAL_QUANTITY','COATING_DATE','COATING_MC_NO',
            'MIN_TB_CONTENT','TOTAL_MAGNET_WEIGHT','COATING_DATE_START','COATING_DATE_FINISH','COATING_DATE2',
            'COATING_MC_NO2','MIN_TB_CONTENT2','TOTAL_MAGNET_WEIGHT2','COATING_DATE_START2','COATING_DATE_FINISH2',
            'COATING_MAX','COATING_MIN','COATING_AVE',
            'COATING_MAX2','COATING_MIN2','COATING_AVE2',
            'FURNACE_MC_NO','CYCLE_NO','BATCH_CYCLE_NO','PATTERN','DATE_START','DATE_FINISH',
            'LENGTH','WIDTH','THICKNESS','MATERIAL_GRADE'
        ];

        $headerFilmPaste = [
            'LAYER','MODEL_CODE','RAW_MATERIAL_CODE','TOTAL_QUANTITY','COATING_DATE','COATING_MC_NO',
            'MIN_TB_CONTENT','TOTAL_MAGNET_WEIGHT','COATING_DATE_START','COATING_DATE_FINISH','COATING_MAX',
            'COATING_MIN','COATING_AVE','FILM_TYPE','FILM_CLASS',
            'FURNACE_MC_NO','CYCLE_NO','BATCH_CYCLE_NO','PATTERN','DATE_START','DATE_FINISH',
            'LENGTH','WIDTH','THICKNESS','MATERIAL_GRADE'
        ];

        $header2ndFilmPaste = [
            'LAYER','MODEL_CODE','RAW_MATERIAL_CODE','TOTAL_QUANTITY','COATING_DATE','COATING_MC_NO',
            'MIN_TB_CONTENT','TOTAL_MAGNET_WEIGHT','COATING_DATE_START','COATING_DATE_FINISH','COATING_MAX',
            'COATING_MIN','COATING_AVE','FILM_TYPE','FILM_CLASS','FILM_TYPE2','FILM_CLASS2',
            'FURNACE_MC_NO','CYCLE_NO','BATCH_CYCLE_NO','PATTERN','DATE_START','DATE_FINISH',
            'LENGTH','WIDTH','THICKNESS','MATERIAL_GRADE'
        ];

        if($has2ndgbdp){
            $header = $header2ndgbdp;
        }

        $lines = collect($outputRows)
            ->map(fn($row) => implode(',', array_map([$this, 'convertToString'], $row)))
            ->prepend(implode(',', $header));

        dd($lines->toArray()); // verify output

        // --- Save ---
        $directory = public_path("files/{$furnace_no} {$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data2.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "";
    }

    private function resolveCoating(
        string $furnace,
        string $massPro,
        bool $isBreaklotLayer,
        bool $isInitialLot,
        $layerKey,
        $coatingLookupLayerNo,
        $model,
        $lotNo,
        $normalizedFurnace,
        bool &$isSecondGbdp = false
    ) {
        $isSecondGbdp = false;

        // -------------------------
        // 1. Breaklot path first
        // -------------------------
        if ($isBreaklotLayer && !$isInitialLot) {

            $coating = BreaklotCoating::where('furnace', $normalizedFurnace)
                ->where('mass_prod', $massPro)
                ->where('layer', (string)$layerKey)
                ->where('model', $model)
                ->where('lot_no', $lotNo)
                ->first();

            if ($coating) {
                return $coating;
            }

            $blSecond = BreaklotSecondCoating::where('furnace', $normalizedFurnace)
                ->where('mass_prod', $massPro)
                ->where('layer', (string)$layerKey)
                ->where('model', $model)
                ->where('lot_no', $lotNo)
                ->first();

            if ($blSecond) {
                $isSecondGbdp = true;

                return [
                    'coating_info_1stgbdp' => is_array($blSecond->coating_info_1stgbdp)
                        ? $blSecond->coating_info_1stgbdp
                        : json_decode($blSecond->coating_info_1stgbdp, true),

                    'coating_info_2ndgbdp' => is_array($blSecond->coating_info_2ndgbdp)
                        ? $blSecond->coating_info_2ndgbdp
                        : json_decode($blSecond->coating_info_2ndgbdp, true),
                ];
            }
        }

        // -------------------------
        // 2. Normal path
        // -------------------------
        $coating = Coating::where('furnace', $normalizedFurnace)
            ->where('mass_prod', $massPro)
            ->where('layer', (string)$coatingLookupLayerNo)
            ->first();

        if ($coating) {
            return $coating;
        }

        $gbdp = GbdpSecondCoating::where('furnace', $normalizedFurnace)
            ->where('mass_prod', $massPro)
            ->where('layer', (string)$coatingLookupLayerNo)
            ->first();

        if ($gbdp) {
            $isSecondGbdp = true;

            return [
                'coating_info_1stgbdp' => is_array($gbdp->coating_info_1stgbdp)
                    ? $gbdp->coating_info_1stgbdp
                    : json_decode($gbdp->coating_info_1stgbdp, true),

                'coating_info_2ndgbdp' => is_array($gbdp->coating_info_2ndgbdp)
                    ? $gbdp->coating_info_2ndgbdp
                    : json_decode($gbdp->coating_info_2ndgbdp, true),
            ];
        }

        return null;
    }

    //dd($lines->toArray()); // verify output

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

        $layerOrder = ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'T'];

        foreach ($layerOrder as $layer) {

            $excess = ExcessLayers::where('mass_prod', $massPro)
                ->where('furnace', $formattedFurnace)
                ->where('layer', (string)$layer)
                ->first();

            if (!$excess) {
                continue;
            }

            $excessData = is_array($excess->layer_data)
                ? $excess->layer_data
                : json_decode($excess->layer_data, true);

            if (empty($excessData)) {
                continue;
            }

            $modelRow = collect($excessData)->firstWhere('rowTitle', 'MODEL:');
            $lotRow   = collect($excessData)->firstWhere('rowTitle', 'LT. No.:');

            if (!$modelRow || !$lotRow) {
                continue;
            }

            foreach (range('A', 'K') as $letter) {

                $model = $modelRow['data'][$letter] ?? null;
                $lotNo = $lotRow['data'][$letter] ?? null;

                if (!$model || !$lotNo) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Find MAIN layer in MassProduction
                |--------------------------------------------------------------------------
                */

                $mainLayer = null;

                foreach ($layerOrder as $searchLayer) {

                    $column = $searchLayer === 'T'
                        ? 'layer_9_5'
                        : "layer_{$searchLayer}";

                    $json = $massProdData->$column ?? null;

                    if (!$json) {
                        continue;
                    }

                    $layerData = json_decode($json, true);

                    if (empty($layerData)) {
                        continue;
                    }

                    $searchModelRow = collect($layerData)
                        ->firstWhere('rowTitle', 'MODEL:');

                    $searchLotRow = collect($layerData)
                        ->firstWhere('rowTitle', 'LT. No.:');

                    if (!$searchModelRow || !$searchLotRow) {
                        continue;
                    }

                    foreach (range('A', 'K') as $slot) {

                        if (
                            ($searchModelRow['data'][$slot] ?? null) === $model
                            &&
                            ($searchLotRow['data'][$slot] ?? null) === $lotNo
                        ) {
                            $mainLayer = $searchLayer;
                            break 2;
                        }
                    }
                }

                if ($mainLayer === null) {

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Find TPM rows belonging to the MAIN layer
                |--------------------------------------------------------------------------
                */
                    $mainLayerFilter = $mainLayer === 'T'
                    ? '9.5'
                    : (string)$mainLayer;

                /*
                |--------------------------------------------------------------------------
                | Find TPM serials from TPMDataCategory
                |--------------------------------------------------------------------------
                */

                $key = $model . '|' . $lotNo . '|' . $massPro;

                if (isset($processed[$key])) {
                    continue;
                }

                $processed[$key] = true;

                /*
                |--------------------------------------------------------------------------
                | Resolve serial (TPMDataCategory)
                |--------------------------------------------------------------------------
                */
                $serial = TPMDataCategory::where('actual_model', $model)
                    ->where('jhcurve_lotno', $lotNo)
                    ->where('massprod_name', $massPro)
                    ->value('tpm_data_serial');

                if (!$serial) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Fetch TPM rows
                |--------------------------------------------------------------------------
                */
                $matchedRows = TPMData::with('remark', 'category')
                    ->where('mass_prod', $massPro)
                    ->where('furnace', $formattedFurnace)
                    ->where('layer_no', $mainLayerFilter)
                    ->where('serial_no', $serial)
                    ->get();

                /*
                |--------------------------------------------------------------------------
                | HARD DEDUP BEFORE PUSHING (important)
                |--------------------------------------------------------------------------
                */
                $matchedRows = $matchedRows->unique('id');

                /*
                |--------------------------------------------------------------------------
                | Grouping (safe push)
                |--------------------------------------------------------------------------
                */
                foreach ($matchedRows as $mainRow) {

                    $layerKey = ($layer === 'T') ? '9.5' : (string)$layer;

                    $groupedByLayer[$layerKey] ??= collect();

                    $exists = $groupedByLayer[$layerKey]
                        ->contains(fn ($r) => $r->id === $mainRow->id);

                    if (!$exists) {
                        $duplicate = $mainRow->replicate();
                        $duplicate->layer_no = $layerKey;

                        $groupedByLayer[$layerKey]->push($duplicate);
                    }
                }
            }
        }

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

    //dd($lines);


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
