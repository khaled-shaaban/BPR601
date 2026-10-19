<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportComments\IndexRequest;
use App\Http\Requests\ReportComments\StoreRequest;
use App\Repositories\ReportCommentRepository;
use App\Repositories\ReportRepository;

class ReportCommentController extends Controller
{
    public function __construct(
        protected ReportRepository $reports,
        protected ReportCommentRepository $repository,
    ) {
    }

    public function index(IndexRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('view', $report);

        return $this->respondWithData($report->comments()->with('user:id,name')->get());
    }

    public function store(StoreRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('view', $report);

        $comment = $this->repository->add($report, $request->user(), $request->validated('body'));

        return $this->respondWithData($comment->load('user:id,name'));
    }
}
