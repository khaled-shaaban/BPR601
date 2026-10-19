<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function index(array|null $filters = [], int|null $perPage = 10): LengthAwarePaginator
    {
        return $this->model->search($filters)
            ->with('roles:id,name')
            ->orderBy('name')
            ->paginate($perPage);
    }
}
