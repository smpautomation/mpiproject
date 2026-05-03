<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LayerDeleteRequest extends Model
{
    use HasFactory;

    protected $table = "layer_delete_requests";
    protected $guarded = [];
}
