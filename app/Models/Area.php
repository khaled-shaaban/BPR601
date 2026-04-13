<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code', 'latitude', 'longitude', 'city_id'])]
class Area extends Model
{
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
