<?php

namespace App\Services;

use App\Models\TpmData;
use Illuminate\Support\Facades\File;

class TxtExportService
{
    public function exportData3(string $furnace_no)
    {
        
        $tpmData = TpmData::with('remark', 'category')
        ->where('furnace_no', $furnace_no)
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

        $directory = public_path("files/{$furnace_no}");
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