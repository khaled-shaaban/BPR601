<?php

use App\Http\Controllers\ReportCommentController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'reports/{report_id}/comments',
    'as' => 'reports.comments.',
    'controller' => ReportCommentController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
});
