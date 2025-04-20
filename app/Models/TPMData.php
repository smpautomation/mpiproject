<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPMData extends Model
{
    use HasFactory;
    protected $table = 'tpm_data';
    protected $guarded = [];
    protected $casts = [
        'x' => 'array',
        'y' => 'array'
    ];

    public function remark()
    {
        return $this->hasOne(TPMDataRemark::class, 'tpm_data_serial', 'serial_no');
    }
    public function aggregateFunctions()
    {
        return $this->hasOne(TPMDataAggregateFunctions::class, 'tpm_data_serial', 'serial_no');
    }
    public function reportData()
    {
        return $this->hasOne(ReportData::class, 'tpm_data_serial', 'serial_no');
    }
    public function furnaceData()
    {
        return $this->hasOne(FurnaceData::class, 'furnace_id', 'furnace_id');
    }
    public function standardData()
    {
        return $this->hasOne(StandardData::class, 'tpm_data_serial', 'serial_no');
    }
    public function dataInstructions()
    {
        return $this->hasOne(DataInstructions::class, 'tpm_data_serial', 'serial_no');
    }

    public function dataInstructionsAggregate()
    {
        return $this->hasOne(DataInstructionsAggregate::class, 'tpm_data_serial', 'serial_no');
    }

    public function mieGxDataInstructions()
    {
        return $this->hasOne(MieGxDataInstructions::class, 'tpm_data_serial', 'serial_no');
    }
    public function mieGxDataInstructionsAggregate()
    {
        return $this->hasOne(MieGxDataInstructionsAggregate::class, 'tpm_data_serial', 'serial_no');
    }
    
}
