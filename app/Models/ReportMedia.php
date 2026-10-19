<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

#[Fillable(['report_id', 'media_type', 'media_url', 'media_name', 'media_size'])]
class ReportMedia extends Model
{
    use HasFactory;

    protected $table = 'report_media';

    protected $appends = ['url'];

    protected $casts = [
        'id' => 'integer',
        'report_id' => 'integer',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Public URL for the stored file (requires `php artisan storage:link`).
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->media_url ? Storage::disk('public')->url($this->media_url) : null,
        );
    }
}
