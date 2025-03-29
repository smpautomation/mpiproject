<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    protected $table = 'report_data';
    protected $guarded = [];

    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_serial', 'serial_no'); // foreign key
    }
}
