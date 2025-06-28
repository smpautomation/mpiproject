<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GxModel extends Model
{
    use HasFactory;
    protected $table = "gx_models";
    protected $guarded = [];
}
