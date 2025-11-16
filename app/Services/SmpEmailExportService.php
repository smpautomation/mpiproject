<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Services\SmpDataService;

class SmpEmailExportService
{
    protected SmpDataService $dataService;

    public function __construct(SmpDataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function export(string $massPro, string $furnace): string
    {
        Log::info("SMP Excel export started for massPro='{$massPro}', furnace='{$furnace}'");

        try {
            $flattened = $this->dataService->getFlattenedData($furnace, $massPro);
            $rowCount = count($flattened);
            Log::info("Flattened data retrieved, rows={$rowCount}");
            Log::info(print_r($flattened, true)); // Debug content

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $headers = $rowCount > 0 ? array_keys(reset($flattened)) : [];

            // Helper: convert 1-based column number to Excel column letter
            $columnLetter = fn(int $col): string => \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);

            // Write headers
            foreach ($headers as $colIndex => $header) {
                $sheet->setCellValue($columnLetter($colIndex + 1) . '1', $header);
            }

            // Write data
            $currentRow = 2;
            foreach ($flattened as $layer) {
                $col = 1;
                foreach ($headers as $header) {
                    $sheet->setCellValue($columnLetter($col++) . $currentRow, $layer[$header] ?? '');
                }
                $currentRow++;
            }

            $dir = public_path("files/{$massPro}");
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0777, true);
                Log::info("Created directory: {$dir}");
            }

            $safeFileName = str_replace(' ', '_', $massPro) . '_SMP.xlsx';
            $filePath = "{$dir}/{$safeFileName}";
            Log::info("Saving Excel to: {$filePath}");

            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);

            Log::info("SMP Excel successfully saved: {$filePath}");

            return $filePath;
        } catch (\Throwable $e) {
            Log::error("SMP Excel generation failed for massPro='{$massPro}', furnace='{$furnace}': " . $e->getMessage());
            throw $e;
        }
    }
}
