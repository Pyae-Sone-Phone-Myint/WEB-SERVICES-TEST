<?php

use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('users')->controller(UserController::class)->group(function () {
    Route::get('profile', 'userProfile');
    Route::get('staffs',"staffs");
});
