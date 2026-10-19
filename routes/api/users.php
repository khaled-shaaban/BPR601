<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'users',
    'as' => 'users.',
    'controller' => UserController::class,
    'middleware' => 'auth:sanctum',
], function () {
    // Lightweight list of admins for the assign picker — available to any admin.
    Route::get('/assignees', 'assignees')->middleware('role:admin|super_admin')->name('assignees');

    // Full user management — super admins only.
    Route::get('/', 'index')->middleware('role:super_admin')->name('index');
    Route::post('/', 'store')->middleware('role:super_admin')->name('store');
    Route::patch('/{id}/role', 'updateRole')->middleware('role:super_admin')->name('role');
});
