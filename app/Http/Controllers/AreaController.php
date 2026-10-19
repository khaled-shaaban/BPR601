<?php

namespace App\Http\Controllers;

use App\Http\Requests\Areas\DeleteRequest;
use App\Http\Requests\Areas\IndexRequest;
use App\Http\Requests\Areas\ShowRequest;
use App\Http\Requests\Areas\StoreRequest;
use App\Http\Requests\Areas\UpdateRequest;
use App\Repositories\AreaRepository;

class AreaController extends Controller
{
    public function __construct(protected AreaRepository $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        $areas = $this->repository->index($request->validated('filters'), $request->validated('per_page'));
        return $this->respondWithPagination($areas);
    }

    public function show(ShowRequest $request, int $id)
    {
        return $this->respondWithData($this->repository->find($id));
    }

    public function store(StoreRequest $request)
    {
        try {
            return $this->respondWithData($this->repository->create($request->validated()));
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    public function update(UpdateRequest $request, int $id)
    {
        try {
            return $this->respondWithData($this->repository->update($request->validated(), $id));
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    public function delete(DeleteRequest $request, int $id)
    {
        try {
            $this->repository->delete($id);
            return $this->respondWithSuccess();
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }
}
