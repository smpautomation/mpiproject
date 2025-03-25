<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionData extends Model
{
    /** @use HasFactory<\Database\Factories\InspectionDataFactory> */
    use HasFactory;

    protected $table = "inspection_data";
    protected $guarded = [];
}
