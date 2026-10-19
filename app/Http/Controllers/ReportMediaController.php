<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportMedia\DeleteRequest;
use App\Http\Requests\ReportMedia\IndexRequest;
use App\Http\Requests\ReportMedia\StoreRequest;
use App\Repositories\ReportMediaRepository;
use App\Repositories\ReportRepository;

class ReportMediaController extends Controller
{
    public function __construct(
        protected ReportRepository $reports,
        protected ReportMediaRepository $repository,
    ) {
    }

    public function index(IndexRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('view', $report);

        return $this->respondWithData($report->media);
    }

    public function store(StoreRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('update', $report);

        $media = $this->repository->storeForReport($report, $request->file('files'));

        return $this->respondWithData($media);
    }

    public function delete(DeleteRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('update', $report);

        $this->repository->deleteForReport($report, (int) $request->media);

        return $this->respondWithSuccess();
    }
}
