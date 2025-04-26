<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NSARemark extends Model
{
    protected $table = 'nsa_remarks';
    protected $guarded = [];

    public function nsa()
    {
        return $this->belongsTo(TPMData::class, 'nsa_id', 'id'); // foreign key
    }
}
