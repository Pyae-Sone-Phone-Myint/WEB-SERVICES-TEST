<?php

use App\Http\Controllers\Departments\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::apiResource('departments', DepartmentController::class);
