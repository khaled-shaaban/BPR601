<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable(['report_id', 'user_id', 'type', 'body'])]
class ReportComment extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'report_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
