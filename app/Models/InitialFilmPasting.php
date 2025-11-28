<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InitialFilmPasting extends Model
{
    protected $table = 'initial_film_pastings';
    protected $guarded = [];

    protected $casts = [
        'h_line_parameters' => 'array',
        't_line_parameters' => 'array',
        'setter_sand'       => 'boolean',
    ];
}
