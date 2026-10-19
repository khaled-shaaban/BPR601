<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Notifications\Notification;

class ReportReceived extends Notification
{
    public function __construct(public Report $report)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'report_received',
            'report_id' => $this->report->id,
            'title' => $this->report->title,
            'message' => "تم استلام بلاغك: «{$this->report->title}». سيتم مراجعته قريبًا.",
        ];
    }
}
