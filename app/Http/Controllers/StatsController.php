<?php

namespace App\Http\Controllers;

use App\Repositories\StatsRepository;

class StatsController extends Controller
{
    public function __construct(protected StatsRepository $repository)
    {
    }

    public function index()
    {
        return $this->respondWithData($this->repository->overview());
    }
}
