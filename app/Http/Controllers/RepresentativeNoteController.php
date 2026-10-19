<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRepresentativeNotes\IndexRequest;
use App\Http\Requests\ReportRepresentativeNotes\StoreRequest;
use App\Repositories\ReportRepository;

class RepresentativeNoteController extends Controller
{
    public function __construct(protected ReportRepository $reports)
    {
    }

    public function index(IndexRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('view', $report);

        return $this->respondWithData($report->representativeNotes()->with('user:id,name')->get());
    }

    public function store(StoreRequest $request)
    {
        $report = $this->reports->find($request->report_id);
        $this->authorize('addRepresentativeNote', $report);

        $note = $report->representativeNotes()->create([
            'user_id' => $request->user()->id,
            'status' => $report->status,
            'body' => $request->validated('body'),
        ]);

        return $this->respondWithData($note->load('user:id,name'));
    }
}
