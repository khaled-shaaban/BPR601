<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Notifications\Notification;

class ReportStatusChanged extends Notification
{
    public function __construct(
        public Report $report,
        public string $from,
        public string $to,
    ) {
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
            'type' => 'report_status_changed',
            'report_id' => $this->report->id,
            'title' => $this->report->title,
            'from' => $this->from,
            'to' => $this->to,
            'message' => "تم تحديث حالة بلاغك: «{$this->report->title}».",
        ];
    }
}
