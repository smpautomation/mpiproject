<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NSACategory extends Model
{
    protected $table = 'nsa_category';
    protected $guarded = [];
    public function nsa()
    {
        return $this->belongsTo(NormalSecAdditionals::class, 'nsa_serial', 'serial_no'); // foreign key
    }
}
