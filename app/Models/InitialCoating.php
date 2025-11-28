<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InitialCoating extends Model
{
    protected $table = 'initial_coatings';
    protected $guarded = [];
    protected $casts = [
        'coating_data' => 'array',
        'coating_date' => 'date',
        'time_start' => 'datetime:H:i:s',
        'time_finished' => 'datetime:H:i:s',
    ];
}
