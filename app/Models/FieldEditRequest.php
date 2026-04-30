<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FieldEditRequest extends Model
{
    use HasFactory;
    protected $table = "field_edit_requests";
    protected $guarded = [];
}
