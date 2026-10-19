<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\{Disableable, Searchable};

#[Fillable(['name', 'guard_name'])]
class Role extends Model
{
    use Disableable, Searchable, HasFactory;
}
