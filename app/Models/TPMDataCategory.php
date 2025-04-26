<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPMDataCategory extends Model
{
    protected $table = 'tpm_data_category';
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_serial', 'serial_no'); // foreign key
    }
}
