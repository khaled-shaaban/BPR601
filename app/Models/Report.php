<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ReportStatus;
use App\Enums\ReportPriority;
use App\Enums\ReportTypes;
use App\Traits\Disableable;
use App\Traits\Searchable;

#[Fillable(['user_id', 'assigned_to', 'authority_id', 'area_id', 'latitude', 'longitude', 'status', 'priority', 'title', 'description', 'type', 'date'])]
class Report extends Model
{
    use HasFactory, Disableable, Searchable;

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'assigned_to' => 'integer',
        'authority_id' => 'integer',
        'area_id' => 'integer',
        'date' => 'datetime',
        'longitude' => 'string',
        'latitude' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function authority()
    {
        return $this->belongsTo(Authority::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function media()
    {
        return $this->hasMany(ReportMedia::class);
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class)->orderBy('created_at');
    }

    public function representativeNotes()
    {
        return $this->hasMany(RepresentativeNote::class)->orderBy('created_at', 'desc');
    }
}
