<?php

namespace App\Http\Controllers;

use App\Enums\AppRoles;
use App\Events\UserNotification;
use App\Http\Requests\Reports\IndexRequest;
use App\Http\Requests\Reports\ShowRequest;
use App\Http\Requests\Reports\StoreRequest;
use App\Http\Requests\Reports\UpdateRequest;
use App\Http\Requests\Reports\DeleteRequest;
use App\Models\Report;
use App\Models\User;
use App\Notifications\NewReportSubmitted;
use App\Notifications\ReportReceived;
use App\Repositories\ReportRepository;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function __construct(protected ReportRepository $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        $filters = $request->validated('filters') ?? [];
        $user = $request->user();

        if ($user->isRepresentative()) {
            // Their authority's reports PLUS any they filed themselves.
            $reports = $this->repository->indexForRepresentative($filters, $request->validated('per_page'), $user);
            return $this->respondWithPagination($reports);
        }

        if (! $user->isAdmin()) {
            // Citizens see only their own reports.
            $filters['user_id'] = $user->id;
        }

        $reports = $this->repository->index($filters, $request->validated('per_page'));
        return $this->respondWithPagination($reports);
    }

    public function show(ShowRequest $request)
    {
        $report = $this->repository->find($request->id);
        $this->authorize('view', $report);
        return $this->respondWithData($report->load(['media', 'user:id,name', 'assignee:id,name']));
    }

    public function store(StoreRequest $request)
    {
        // Administrators manage reports; they don't file them.
        if ($request->user()->isAdmin()) {
            return $this->respondWithFailure('Administrators cannot submit reports.', 403);
        }

        try {
            $report = $this->repository->create($request->validated());
            $this->notifyNewReport($report);
            $this->notifyReporterReceived($report);
            return $this->respondWithData($report);
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    public function update(UpdateRequest $request)
    {
        $report = $this->repository->find($request->id);
        $this->authorize('update', $report);
        try {
            $report = $this->repository->update($request->validated(), $request->id);
            return $this->respondWithData($report);
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    public function delete(DeleteRequest $request)
    {
        $report = $this->repository->find($request->id);
        $this->authorize('delete', $report);
        try {
            $this->repository->delete($request->id);
            return $this->respondWithSuccess();
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    /**
     * Notify all administrators and the target authority's representatives
     * (persistent DB notification + guarded real-time push).
     */
    private function notifyNewReport(Report $report): void
    {
        $admins = User::role([AppRoles::ADMIN->value, AppRoles::SUPER_ADMIN->value])->get();
        $reps = User::role(AppRoles::REPRESENTATIVE->value)
            ->where('authority_id', $report->authority_id)
            ->get();

        $recipients = $admins->concat($reps)->unique('id');

        Notification::send($recipients, new NewReportSubmitted($report));

        foreach ($recipients as $recipient) {
            try {
                event(new UserNotification($recipient->id, [
                    'type' => 'new_report',
                    'report_id' => $report->id,
                    'message' => "تم استلام بلاغ جديد: «{$report->title}».",
                ]));
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }

    /**
     * Confirm to the reporter that their report was received — respecting the
     * "notify on received" preference (opt-out; on by default).
     */
    private function notifyReporterReceived(Report $report): void
    {
        $reporter = $report->user;

        if (! $reporter || ! $reporter->notify_on_received) {
            return;
        }

        $reporter->notify(new ReportReceived($report));

        try {
            event(new UserNotification($reporter->id, [
                'type' => 'report_received',
                'report_id' => $report->id,
                'message' => "تم استلام بلاغك: «{$report->title}».",
            ]));
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
