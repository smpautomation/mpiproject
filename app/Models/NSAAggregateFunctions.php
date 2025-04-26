<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NSAAggregateFunctions extends Model
{
    protected $table = 'nsa_aggregate_functions';
    protected $guarded = [];
    protected $casts = [
        'average' => 'array',
        'maximum' => 'array',
        'minimum' => 'array',
        'ng_counter' => 'array'
    ];

    public function nsa()
    {
        return $this->belongsTo(NormalSecAdditionals::class, 'nsa_serial', 'serial_no'); // foreign key
    }
}
