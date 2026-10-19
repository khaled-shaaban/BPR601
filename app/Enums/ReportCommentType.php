<?php

namespace App\Enums;

enum ReportCommentType: string
{
    case COMMENT = 'comment';
    case STATUS_CHANGE = 'status_change';
    case ASSIGNMENT = 'assignment';
}
