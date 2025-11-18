<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLogs extends Model
{
    protected $table = "error_logs";
    protected $guarded = [];

    protected $casts = [
        'details' => 'array', // only 'details' needs casting to array
    ];
}
