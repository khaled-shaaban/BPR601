<?php

use App\Http\Controllers\ReportMediaController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'reports/{report_id}/media',
    'as' => 'reports.media.',
    'controller' => ReportMediaController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::delete('/{media}', 'delete')->name('delete');
});
