<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobModel extends Model
{
    use HasFactory;
    protected $table = "rob_models";
    protected $guarded = [];
}
