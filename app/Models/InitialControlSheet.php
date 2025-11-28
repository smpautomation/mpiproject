<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InitialControlSheet extends Model
{
    protected $table = "initial_control_sheets";
    protected $guarded = [];

    protected $casts = [
        'layer_data' => 'array',
        'excess_data' => 'array',
    ];
}
