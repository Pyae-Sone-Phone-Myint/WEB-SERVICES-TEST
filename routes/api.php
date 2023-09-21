<?php

use App\Http\Middleware\AcceptJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::prefix('v1')->middleware(AcceptJson::class)->group(function () {

    // Include the authentication routes
    include __DIR__ . '/Auth/auth.php';
    Route::middleware('auth:sanctum')->group(function () {
        // Include the user routes
        include __DIR__ . '/Users/user.php';

        // Include the role routes
        include __DIR__ . "/Roles/role.php";

        // Include the permission routes
        include __DIR__ . "/Permissions/permissions.php";

        // Include the department routes
        include __DIR__ . "/Departments/departments.php";
    });
});
