<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPMData extends Model
{
    protected $table = 'tpm_data';
    protected $guarded = [];

    public function remark()
    {
        return $this->hasOne(TPMDataRemark::class, 'id'); // assuming 'tpm_data_id' is the foreign key in tpm_data_remarks
    }
}
