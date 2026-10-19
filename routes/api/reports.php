<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportWorkflowController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'reports',
    'as' => 'reports.',
    'controller' => ReportController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::post('/', 'store')->name('store');
    Route::put('/{id}', 'update')->name('update');
    Route::delete('/{id}', 'delete')->name('delete');
});

// Workflow actions — authorization handled per-action.
Route::group([
    'prefix' => 'reports',
    'as' => 'reports.',
    'controller' => ReportWorkflowController::class,
    'middleware' => 'auth:sanctum',
], function () {
    // Admins (any authority) and representatives (their authority) — gated by ReportPolicy.
    Route::patch('/{id}/status', 'updateStatus')->name('status');
    // Assignment is administrators only.
    Route::patch('/{id}/assign', 'assign')->middleware('role:admin|super_admin')->name('assign');
});
