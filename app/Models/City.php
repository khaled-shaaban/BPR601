<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\{Disableable, Searchable};

#[Fillable(['name', 'code', 'latitude', 'longitude', 'radius', 'active'])]
class City extends Model
{
    use Disableable, Searchable, HasFactory;

    protected $casts = [
        'id' => 'integer',
        'radius' => 'integer',
        'active' => 'boolean',
    ];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }
}
