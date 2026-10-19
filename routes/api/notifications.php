<?php

use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'notifications',
    'as' => 'notifications.',
    'controller' => NotificationController::class,
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/unread-count', 'unreadCount')->name('unread-count');
    Route::post('/read-all', 'markAllAsRead')->name('read-all');
    Route::post('/{id}/read', 'markAsRead')->name('read');
});
