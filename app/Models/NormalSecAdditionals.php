<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormalSecAdditionals extends Model
{
    protected $table = 'normal_sec_additionals';
    protected $guarded = [];
    protected $casts = [
        'x' => 'array',
        'y' => 'array'
    ];
    public function remark()
    {
        return $this->hasOne(NSARemark::class, 'nsa_id', 'id');
    }
    public function aggregateFunctions()
    {
        return $this->hasOne(NSAAggregateFunctions::class, 'nsa_serial', 'serial_no');
    }
    public function category()
    {
        return $this->hasOne(NSACategory::class, 'nsa_serial', 'serial_no');
    }
}
