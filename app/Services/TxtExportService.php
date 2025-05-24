<?php

namespace App\Services;

use App\Models\TpmData;
use Illuminate\Support\Facades\File;

class TxtExportService
{
    public function exportData1(string $furnace_no, string $massPro)
    {
        // Step 1: Get the latest date for the specified furnace
        $dateToGet = TpmData::where('furnace_no', $furnace_no)
            ->orderBy('date', 'desc')
            ->value('date');

        // Step 2: Fetch TPM data for that furnace and date, including category and boxes
        $tpmData = TpmData::with(['category', 'boxes' => function ($q) {
                $q->whereIn('box_letter', range('A', 'K'));
            }])
            ->where('furnace_no', $furnace_no)
            ->where('date', $dateToGet)
            ->get();

        if ($tpmData->isEmpty()) {
            return 'No data found.';
        }

        // Step 3: Prepare layer/area structure as integers 10 down to 1
        $layers = range(1, 10);
        rsort($layers); // [10, 9, 8, ..., 1]

        $areas = range('A', 'K');

        // Step 4: Build a quick index from layer_no + box_letter to data (using numeric layer keys)
        $boxMap = [];

        foreach ($tpmData as $tpm) {
            $layerKey = $tpm->layer_no; // keep as integer, 1 to 10

            $category = $tpm->category;
            $modelName = $category->actual_model ?? '';
            $lotNo = $category->jhcurve_lotno ?? '';
            $rawMaterialCode = $category->raw_material_code ?? '';
            $modelCode = $tpm->code_no;
            $coatingMcNo = $tpm->furnace_no;

            foreach ($tpm->boxes as $box) {
                $boxMap[$layerKey][$box->box_letter] = [
                    'MODEL_NAME' => $modelName,
                    'COATING_MC_NO' => $coatingMcNo,
                    'LOT_NO' => $lotNo,
                    'QTY' => $box->quantity,
                    'COATING' => 3,
                    'WT' => $box->weight,
                    'BOX_NO' => $box->box_no,
                    'MODEL_CODE' => $modelCode,
                    'RAW_MATERIAL_CODE' => $rawMaterialCode,
                ];
            }
        }

        // Step 5: Build rows using fixed structure, converting layer 10 to 'T' only here
        $outputRows = [];

        foreach ($layers as $layer) {
            $outputLayer = $layer === 10 ? 'T' : (string)$layer;

            foreach ($areas as $area) {
                $row = data_get($boxMap, "$layer.$area", [
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

                $outputRows[] = array_merge([
                    'LAYER' => $outputLayer,
                    'AREA' => $area,
                ], $row);
            }
        }

        // Step 6: Format into lines
        $header = "LAYER,AREA,MODEL_NAME,COATING_MC_NO,LOT_NO,QTY,COATING,WT,BOX_NO,MODEL_CODE,RAW_MATERIAL_CODE";
        $lines = collect($outputRows)
            ->map(fn($row) => implode(',', $row))
            ->prepend($header);

        // Step 7: Save file
        $directory = public_path("files/{$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
        $filePath = "{$directory}/Data1.txt";
        File::put($filePath, implode("\n", $lines->toArray()));

        return "Exported successfully to: {$filePath}";
    }


    public function exportData2(string $furnace_no)
    {

    }

    public function exportData3(string $furnace_no, string $massPro)
    {
        $dateToGet = TpmData::where('furnace_no', $furnace_no)
            ->orderBy('date', 'desc')
            ->value('date');

        $tpmData = TpmData::with('remark', 'category')
        ->where('furnace_no', $furnace_no)
        ->where('date', $dateToGet)
        ->get();

        if ($tpmData->isEmpty()) {
            return 'No data found.';
        }

        $firstItem = $tpmData->first();
        $excludeFields = [
            'id',
            'created_at',
            'updated_at',
            'serial_no',
            'x',
            'y',
            'furnace_id',
            'temperature',
            'data_status',
            'order_no',
            'Density',
            'HRX',
            'MRX',
            'HRY',
            'MRY',
            'IHKA',
            'MRA',
            'IHKB',
            'MRB',
            'IHKC',
            'MRC',
            'HR',
            'HRO'
        ];

        $baseAttributes = collect($firstItem->getAttributes())
                    ->except($excludeFields)
                    ->keys();
        $remarkAttributes = $firstItem->remark
            ? collect($firstItem->remark->getAttributes())
                ->except(['id',
                                'created_at',
                                'updated_at',
                                ])
                ->keys()
            : collect();

        $fixedFront = [
            'layer_no', 'lot_no', 'date', 'code_no', 'type', 'press_1', 'press_2',
            'machine_no', 'sintering_furnace_no', 'furnace_no', 'zone', 'pass_no'
        ];

        $customOrder = [
            'Br', 'Br_remarks', 'iHc', 'iHc_remarks', 'iHk', 'iHk_remarks',
            'BHMax', 'BHMax_remarks', 'iHr95', 'iHr95_remarks', 'iHr98', 'iHr98_remarks',
            'iHkiHc', 'iHkiHc_remarks', 'Br4pai', 'Br4pai_remarks', 'bHc', 'bHc_remarks',
            'Squareness', 'Squareness_remarks', '4paiId', '4paiId_remarks',
            '4paiIs', '4paiIs_remarks', '4paiIa', '4paiIa_remarks', 'Tracer'
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

        $lines = [];
        $lines[] = implode(',', $headers); // Header line

        foreach ($tpmData as $item) {
            $remark = $item->remark;
            //$category = $item->category;
            $row = [];

            $row[] = $this->convertToString($item->layer_no);
            $lot_no = trim(
                ($item->press_1 ?? '') . ' ' .
                ($item->press_2 ?? '') . ' ' .
                ($item->machine_no ?? '')
            );
            $row[] = $lot_no;


            foreach ($headers as $column) {
                if (in_array($column, ['layer_no', 'lot_no'])) {
                    continue; // already added
                }

                if (isset($item->$column)) {
                    $row[] = $this->convertToString($item->{$column} ?? '0');
                } elseif ($remark && property_exists($remark, $column)) {
                    $row[] = $this->convertToString($remark->{$column} ?? '0');
                } else {
                    $row[] = '0';
                }
            }

            // Escape values (commas, newlines)
            $escapedRow = array_map(function ($value) {
                $value = $this->convertToString($value);
                $value = str_replace(["\r", "\n"], [' ', ' '], $value);
                return str_contains($value, ',') ? "\"$value\"" : $value;
            }, $row);

            $lines[] = implode(',', $escapedRow);
        }

        $directory = public_path("files/{$massPro}");
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/Data3.txt";
        File::put($filePath, implode("\n", $lines));

        return "Exported successfully to: {$filePath}";



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
