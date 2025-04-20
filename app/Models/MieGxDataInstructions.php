<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MieGxDataInstructions extends Model
{
    protected $table = 'mie_gx_data_instructions';
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_serial', 'serial_no'); // foreign key
    }
}
