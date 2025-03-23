<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayerData extends Model
{
    protected $table = "layer_data";
    protected $guarded = [];
    public function tpmData()
    {
        return $this->belongsTo(TPMData::class, 'layer_no', 'layer_no');
    }
    public function furnaceData()
    {
        return $this->belongsTo(FurnaceData::class, 'furnace_id', 'furnace_id');
    }
}
