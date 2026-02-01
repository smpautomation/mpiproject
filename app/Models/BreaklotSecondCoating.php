<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreaklotSecondCoating extends Model
{
    protected $table = "breaklot_second_coatings";
    protected $guarded = [];

    protected $casts = [
        'coating_info_1stgbdp' => 'array',
        'coating_info_2ndgbdp' => 'array',
        'coating_data_1stgbdp' => 'array',
        'coating_data_2ndgbdp' => 'array',
    ];
}
