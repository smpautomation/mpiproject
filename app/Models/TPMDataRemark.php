<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPMDataRemark extends Model
{
    protected $table = 'tpm_data_remarks';
    protected $guarded = [];

    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_id', 'id'); // foreign key
    }
}
