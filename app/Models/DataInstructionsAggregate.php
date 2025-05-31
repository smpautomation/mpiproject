<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataInstructionsAggregate extends Model
{
    protected $table = 'data_instructions_aggregates';
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_serial', 'serial_no'); // foreign key
    }
}
