<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TxtExportService
{
    public function exportData3(string $serial)
    {
        $tpmData = DB::table('tpm_data')
            ->with('remarks')
            ->where('serial_no', $serial)
            ->get();
        dd($tpmData);
 
 
    }
    public function exportData2(string $serial){

    }
} 