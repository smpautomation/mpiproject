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
        return $this->hasOne(TPMDataRemark::class, 'id');
    }
    public function aggregateFunctions()
    {
        return $this->hasOne(TPMDataAggregateFunctions::class, 'id');
    }
}
