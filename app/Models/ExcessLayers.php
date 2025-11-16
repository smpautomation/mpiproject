<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExcessLayers extends Model
{
    protected $table = "excess_layers";
    protected $guarded = [];

    protected $casts = [
       'layer_data' => 'array',
    ];
}
