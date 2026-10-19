<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
    'controller' => AuthController::class,
], function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', 'me')->name('me');
        Route::post('/logout', 'logout')->name('logout');
        Route::put('/profile', 'updateProfile')->name('profile');
        Route::put('/password', 'updatePassword')->name('password');
        Route::put('/notifications', 'updateNotifications')->name('notifications');
    });
});
