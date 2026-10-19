<?php 

namespace App\Http\Controllers;

use App\Http\Requests\Authorities\IndexRequest;
use App\Http\Requests\Authorities\ShowRequest;
use App\Http\Requests\Authorities\StoreRequest;
use App\Http\Requests\Authorities\UpdateRequest;
use App\Http\Requests\Authorities\DeleteRequest;
use App\Repositories\AuthorityRepository;

class AuthorityController extends Controller
{
    public function __construct(protected AuthorityRepository $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        $authorities = $this->repository->index($request->validated('filters'), $request->validated('per_page'));
        return $this->respondWithPagination($authorities);
    }

    public function show(ShowRequest $request)
    {
        $authority = $this->repository->find($request->id);
        return $this->respondWithData($authority);
    }

    public function store(StoreRequest $request)
    {
        try {
            $authority = $this->repository->create($request->validated());
            return $this->respondWithData($authority);
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    public function update(UpdateRequest $request)
    {
        try {
            $authority = $this->repository->update($request->validated(), $request->id);
            return $this->respondWithData($authority);
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }

    public function delete(DeleteRequest $request)
    {
        try {
            $this->repository->delete($request->id);
            return $this->respondWithSuccess();
        } catch (\Exception $e) {
            return $this->respondWithFailure($e->getMessage());
        }
    }
}