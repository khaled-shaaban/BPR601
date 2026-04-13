<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['report_id', 'media_type', 'media_url', 'media_name', 'media_size'])]
class ReportMedia extends Model
{
    use HasFactory;

    public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
