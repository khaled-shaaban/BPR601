<?php

namespace App\Repositories;

use App\Enums\ReportPriority;
use App\Enums\ReportStatus;
use App\Enums\ReportTypes;
use App\Models\Area;
use App\Models\Authority;
use App\Models\City;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Collection;

class StatsRepository
{
    /**
     * Aggregate dashboard metrics.
     *
     * @return array<string, mixed>
     */
    public function overview(): array
    {
        $byStatus = $this->countBy('status', ReportStatus::cases());
        $byType = $this->countBy('type', ReportTypes::cases());
        $byPriority = $this->countBy('priority', ReportPriority::cases());

        return [
            'totals' => [
                'reports' => Report::count(),
                'users' => User::count(),
                'authorities' => Authority::count(),
                'cities' => City::count(),
                'areas' => Area::count(),
                'open_reports' => ($byStatus[ReportStatus::PENDING->value] ?? 0)
                    + ($byStatus[ReportStatus::IN_PROGRESS->value] ?? 0),
            ],
            'reports_by_status' => $byStatus,
            'reports_by_type' => $byType,
            'reports_by_priority' => $byPriority,
            'reports_by_city' => $this->reportsByCity(),
            'recent_reports' => Report::with(['authority:id,name', 'area:id,name'])
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Count reports grouped by a column, backfilling every enum case with 0.
     *
     * @param  array<int, \BackedEnum>  $cases
     * @return array<string, int>
     */
    private function countBy(string $column, array $cases): array
    {
        // NB: don't alias to `key` — it's a reserved word in MySQL (SQLite
        // tolerates it) and 500s the whole dashboard on production.
        $counts = Report::query()
            ->selectRaw("{$column}, count(*) as total")
            ->groupBy($column)
            ->pluck('total', $column);

        $result = [];
        foreach ($cases as $case) {
            $result[$case->value] = (int) ($counts[$case->value] ?? 0);
        }

        return $result;
    }

    private function reportsByCity(): Collection
    {
        return Report::query()
            ->join('areas', 'reports.area_id', '=', 'areas.id')
            ->join('cities', 'areas.city_id', '=', 'cities.id')
            ->selectRaw('cities.id as city_id, cities.name as city, count(*) as total')
            ->groupBy('cities.id', 'cities.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }
}
