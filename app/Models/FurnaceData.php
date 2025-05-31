<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FurnaceData extends Model
{
    use HasFactory;
    protected $table = "furnace_data";
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'furnace_id', 'furnace_id'); // foreign key
    }
    public function layerData()
    {
        return $this->hasMany(LayerData::class,'furnace_id','furnace_id');
    }
}
