<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilmPastingData extends Model
{
    protected $table = "film_pasting_data";
    protected $guarded = [];

    protected $casts = [
        'h_line_parameters' => 'array',
        't_line_parameters' => 'array',
        'setter_sand' => 'boolean',
    ];
}
