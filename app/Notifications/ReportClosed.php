<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Notifications\Notification;

class ReportClosed extends Notification
{
    public function __construct(
        public Report $report,
        public string $status,
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
            'type' => 'report_closed',
            'report_id' => $this->report->id,
            'title' => $this->report->title,
            'status' => $this->status,
            'message' => "تم إغلاق بلاغك: «{$this->report->title}».",
        ];
    }
}
