<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['name', 'code', 'description', 'icon', 'color', 'area_id', 'address', 'contact_phone_1', 'contact_phone_2', 'active'])]
class Authority extends Model
{
    use HasFactory;

    protected function iconUrl(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value ? asset('storage/icons/' . $value) : null,
        );
    }

    protected function colorHex(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value ? '#' . $value : null,
            set: fn (string $value) => $value ? str_replace('#', '', $value) : null,
        );
    }
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
