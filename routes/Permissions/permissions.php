<?php

use App\Http\Controllers\Permissions\PermissionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('permissions', PermissionController::class);
