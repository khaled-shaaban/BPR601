<?php

use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'stats',
    'as' => 'stats.',
    'controller' => StatsController::class,
    'middleware' => ['auth:sanctum', 'role:admin|super_admin'],
], function () {
    Route::get('/', 'index')->name('index');
});
