<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BhModel extends Model
{
    use HasFactory;
    protected $table = "bh_models";
    protected $guarded = [];
}
