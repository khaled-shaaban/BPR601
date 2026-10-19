<?php

use App\Http\Controllers\AuthorityController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'authorities',
    'as' => 'authorities.',
    'controller' => AuthorityController::class,
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');

    Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
        Route::post('/', 'store')->name('store');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'delete')->name('delete');
    });
});
