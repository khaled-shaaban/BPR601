<?php

namespace App\Repositories;

use App\Enums\ReportCommentType;
use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;

class ReportCommentRepository
{
    public function add(
        Report $report,
        ?User $author,
        string $body,
        ReportCommentType $type = ReportCommentType::COMMENT,
    ): ReportComment {
        return $report->comments()->create([
            'user_id' => $author?->id,
            'type' => $type->value,
            'body' => $body,
        ]);
    }
}
