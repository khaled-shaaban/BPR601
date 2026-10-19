<?php

namespace App\Enums;

enum ReportStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    /**
     * Statuses this status is allowed to transition into.
     *
     * @return array<int, self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::IN_PROGRESS, self::CANCELLED],
            self::IN_PROGRESS => [self::COMPLETED, self::CANCELLED],
            self::COMPLETED, self::CANCELLED => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }

    /**
     * Whether this is a terminal (closing) status — i.e. the report is closed.
     */
    public function isClosing(): bool
    {
        return in_array($this, [self::COMPLETED, self::CANCELLED], true);
    }
}
