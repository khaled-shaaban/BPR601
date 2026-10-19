<?php

namespace App\Http\Controllers;

use App\Enums\ReportCommentType;
use App\Enums\ReportStatus;
use App\Events\UserNotification;
use App\Http\Requests\Reports\AssignRequest;
use App\Http\Requests\Reports\StatusRequest;
use App\Models\User;
use App\Notifications\ReportAssigned;
use App\Notifications\ReportClosed;
use App\Notifications\ReportStatusChanged;
use App\Repositories\ReportCommentRepository;
use App\Repositories\ReportRepository;

class ReportWorkflowController extends Controller
{
    public function __construct(
        protected ReportRepository $repository,
        protected ReportCommentRepository $comments,
    ) {
    }

    public function updateStatus(StatusRequest $request)
    {
        $report = $this->repository->find($request->id);
        $this->authorize('updateStatus', $report);

        $current = ReportStatus::from($report->status);
        $target = ReportStatus::from($request->validated('status'));

        if ($current !== $target && ! $current->canTransitionTo($target)) {
            return $this->respondWithFailure("Cannot change status from {$current->value} to {$target->value}.");
        }

        if ($current !== $target) {
            $report->update(['status' => $target->value]);

            $actor = $request->user();
            $note = $request->validated('note');
            $transition = "Status changed from {$current->value} to {$target->value}.";

            if ($actor->isRepresentative()) {
                // Transition is logged to the shared timeline; the note goes to the
                // separate representative-notes section (distinct from admin notes).
                $this->comments->add($report, $actor, $transition, ReportCommentType::STATUS_CHANGE);
                if ($note) {
                    $report->representativeNotes()->create([
                        'user_id' => $actor->id,
                        'status' => $target->value,
                        'body' => $note,
                    ]);
                }
            } else {
                if ($note) {
                    $transition .= ' ' . $note;
                }
                $this->comments->add($report, $actor, $transition, ReportCommentType::STATUS_CHANGE);
            }

            $this->notifyOwnerOfStatus($report, $current, $target);
        }

        return $this->respondWithData($report->fresh());
    }

    public function assign(AssignRequest $request)
    {
        $report = $this->repository->find($request->id);
        $assignee = User::findOrFail($request->validated('assigned_to'));

        if (! $assignee->isAdmin()) {
            return $this->respondWithFailure('Reports can only be assigned to administrators.');
        }

        $report->update(['assigned_to' => $assignee->id]);

        $this->comments->add(
            $report,
            $request->user(),
            "Assigned to {$assignee->name}.",
            ReportCommentType::ASSIGNMENT,
        );

        $assignee->notify(new ReportAssigned($report));
        $this->pushRealtime($assignee->id, [
            'type' => 'report_assigned',
            'report_id' => $report->id,
            'message' => "تم إسناد بلاغ إليك: «{$report->title}».",
        ]);

        return $this->respondWithData($report->fresh());
    }

    /**
     * Notify the report owner about a status change, honouring their per-event
     * preferences. A move into a terminal status is treated as "closed" and
     * uses the dedicated closed notification/preference.
     */
    private function notifyOwnerOfStatus(\App\Models\Report $report, ReportStatus $current, ReportStatus $target): void
    {
        $owner = $report->user;

        if (! $owner) {
            return;
        }

        if ($target->isClosing()) {
            if (! $owner->notify_on_closed) {
                return;
            }

            $owner->notify(new ReportClosed($report, $target->value));
            $this->pushRealtime($owner->id, [
                'type' => 'report_closed',
                'report_id' => $report->id,
                'message' => "تم إغلاق بلاغك: «{$report->title}».",
            ]);

            return;
        }

        if (! $owner->notify_on_status_change) {
            return;
        }

        $owner->notify(new ReportStatusChanged($report, $current->value, $target->value));
        $this->pushRealtime($owner->id, [
            'type' => 'report_status_changed',
            'report_id' => $report->id,
            'message' => "تم تحديث حالة بلاغك: «{$report->title}».",
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function pushRealtime(int $userId, array $payload): void
    {
        try {
            event(new UserNotification($userId, $payload));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
