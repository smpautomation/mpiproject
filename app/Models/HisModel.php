<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HisModel extends Model
{
    use HasFactory;
    protected $table = "his_models";
    protected $guarded = [];
}
