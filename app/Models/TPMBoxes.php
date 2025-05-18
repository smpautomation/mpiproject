<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPMBoxes extends Model
{
    protected $table = 'tpm_boxes';
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_serial', 'serial_no'); // foreign key
    }
}
