<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TPMData extends Model
{
    protected $table = 'tpm_data';
    protected $guarded = [];
    protected $casts = [
        'x' => 'array',
        'y' => 'array'
    ];

    public function remark()
    {
        return $this->hasOne(TPMDataRemark::class, 'tpm_data_id', 'id');
    }
    public function aggregateFunctions()
    {
        return $this->hasOne(TPMDataAggregateFunctions::class, 'tpm_data_id', 'id');
    }

    public function reportData()
    {
        return $this->hasOne(ReportData::class, 'tpm_data_id', 'id');
    }

    public function furnaceData()
    {
        return $this->hasOne(FurnaceData::class, 'furnace_id', 'furnace_id');
    }
}
