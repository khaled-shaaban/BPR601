<?php

use App\Http\Controllers\RepresentativeNoteController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'reports/{report_id}/representative-notes',
    'as' => 'reports.representative-notes.',
    'controller' => RepresentativeNoteController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
});
