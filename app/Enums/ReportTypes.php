<?php

namespace App\Enums;

enum ReportTypes: string
{
    case CRIMINAL = 'criminal';
    case POLICE = 'police';

    case CYBERCRIME = 'cybercrime';

    case FIRE = 'fire';

    case MEDICAL = 'medical';

    case ENVIRONMENT = 'environment';

    case TRAFFIC = 'traffic';
    case ACCIDENT = 'accident';

    case OTHER = 'other';
}