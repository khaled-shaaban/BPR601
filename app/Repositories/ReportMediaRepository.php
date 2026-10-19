<?php

namespace App\Repositories;

use App\Models\Report;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReportMediaRepository
{
    /**
     * Store uploaded files against a report.
     *
     * @param  array<int, UploadedFile>  $files
     * @return Collection
     */
    public function storeForReport(Report $report, array $files): Collection
    {
        return collect($files)->map(function (UploadedFile $file) use ($report) {
            $path = $file->store('reports/' . $report->id, 'public');

            return $report->media()->create([
                'media_type' => $file->getClientMimeType(),
                'media_url' => $path,
                'media_name' => $file->getClientOriginalName(),
                'media_size' => (string) $file->getSize(),
            ]);
        });
    }

    public function deleteForReport(Report $report, int $mediaId): void
    {
        $media = $report->media()->findOrFail($mediaId);

        Storage::disk('public')->delete($media->media_url);

        $media->delete();
    }
}
