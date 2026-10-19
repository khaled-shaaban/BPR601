<?php 

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     * @param array|null $filters
     * @param int|null $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function index(array|null $filters = [], int|null $perPage = 10): LengthAwarePaginator
    {
        $query = $this->model->search($filters);

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Find a record by id
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function find(int $id): Model|null
    {
        return $this->model->findOrFail($id);
    }
    
    /**
     * Create a new record
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update a record by id
     * @param array $data
     * @param int $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update(array $data, int $id): Model
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }
    
    /**
     * Delete a record by id
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * Activate a record by id
     * @param int $id
     * @return bool
     */
    public function activate(int $id): bool
    {
        return $this->model->findOrFail($id)->activate();
    }

    /**
     * Disable a record by id
     * @param int $id
     * @return bool
     */
    public function disable(int $id): bool
    {
        return $this->model->findOrFail($id)->disable();
    }
}