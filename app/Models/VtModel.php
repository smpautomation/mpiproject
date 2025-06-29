<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtModel extends Model
{
    use HasFactory;
    protected $table = "vt_models";
    protected $guarded = [];
}
