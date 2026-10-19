<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{Disableable, Searchable};

#[Fillable(['name', 'code', 'latitude', 'longitude', 'city_id', 'active'])]
class Area extends Model
{
    use Disableable, Searchable, HasFactory;

    protected $casts = [
        'id' => 'integer',
        'city_id' => 'integer',
        'active' => 'boolean',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}