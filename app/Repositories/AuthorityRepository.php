<?php 

namespace App\Repositories;

use App\Models\Authority;

class AuthorityRepository extends BaseRepository
{
    public function __construct(Authority $model)
    {
        parent::__construct($model);
    }
}