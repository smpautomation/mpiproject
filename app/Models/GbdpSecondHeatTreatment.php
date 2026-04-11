<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GbdpSecondHeatTreatment extends Model
{
    protected $table = "gbdp_second_heat_treatments";
    protected $guarded = [];

    protected $casts = [
        'gbdp_1st' => 'array',
        'gbdp_2nd' => 'array',
    ];
}
