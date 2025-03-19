<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FurnaceData extends Model
{
    protected $table = "furnace_data";
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'furnace_no', 'furnace_no'); // foreign key
    }
    public function layerData()
    {
        return $this->hasMany(LayerData::class,'furnace_no','furnace_no');
    }
}
