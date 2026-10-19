<?php 

namespace App\Repositories;

use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class ReportRepository extends BaseRepository
{
    public function __construct(Report $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Model
    {
        $data['user_id'] = Auth::user()->id;
        // New reports always start as pending; status changes go through the workflow endpoint.
        $data['status'] = ReportStatus::PENDING->value;
        return parent::create($data);
    }

    /**
     * Reports filed to the representative's authority, plus any they filed themselves.
     */
    public function indexForRepresentative(array|null $filters, int|null $perPage, User $representative): LengthAwarePaginator
    {
        return $this->model->search($filters)
            ->where(function ($query) use ($representative) {
                $query->where('authority_id', $representative->authority_id)
                    ->orWhere('user_id', $representative->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}