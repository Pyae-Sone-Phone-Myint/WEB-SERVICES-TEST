<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('users')->controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('logout-all', 'logoutAll');
        Route::post('change-password', 'updatePassword');
    });
});

Route::post('login', [AuthController::class, 'login']);
