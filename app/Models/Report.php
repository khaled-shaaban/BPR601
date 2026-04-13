<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ReportStatus;
use App\Enums\ReportPriority;
use App\Enums\ReportTypes;

#[Fillable(['user_id', 'authority_id', 'latitude', 'longitude', 'status', 'priority', 'title', 'description', 'type', 'date'])]
class Report extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'datetime',
        'status' => ReportStatus::class,
        'priority' => ReportPriority::class,
        'type' => ReportTypes::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function authority()
    {
        return $this->belongsTo(Authority::class);
    }
}
