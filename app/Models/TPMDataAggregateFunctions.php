<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TPMDataAggregateFunctions extends Model
{
    use HasFactory;
    protected $table = 'tpm_data_aggregate_functions';
    protected $guarded = [];
    protected $casts = [
        'average' => 'array',
        'maximum' => 'array',
        'minimum' => 'array',
        'ng_counter' => 'array'
    ];

    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'tpm_data_id', 'id'); // foreign key
    }
}
