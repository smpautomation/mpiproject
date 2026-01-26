<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreaklotFilmpasting extends Model
{
    protected $table = "breaklot_filmpastings";
    protected $guarded = [];

    protected $casts = [
        'h_line_parameters' => 'array',
        't_line_parameters' => 'array',
        'setter_sand' => 'boolean',
    ];
}
