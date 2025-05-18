<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TxtExportService;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(TxtExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function exportData3($furnace_no)
    {
        return $this->exportService->exportData3($furnace_no);
    }

    public function exportData1($furnace_no)
    {
        return $this->exportService->exportData1($furnace_no);
    }
}
