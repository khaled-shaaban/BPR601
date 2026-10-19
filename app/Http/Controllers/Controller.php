<?php

namespace App\Http\Controllers;

use App\Http\Traits\RestfulResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use RestfulResponse, AuthorizesRequests;
}
