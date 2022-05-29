<?php

use App\Http\Controllers\Api\{
    PermisionUserController,
    ResourceController,
    UserController,
};
use App\Http\Controllers\Api\Auth\{
    AuthController,
    RegisterController,
};
use Illuminate\Support\Facades\Route;

// Auth routes
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/auth', [AuthController::class, 'auth']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::get('/me', [AuthController::class, 'me'])->middleware(['auth:sanctum']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Permissions of user routes
    Route::post('/users/permissions', [PermisionUserController::class, 'addPermissionUser']);
    Route::get('/users/{identify}/permissions', [PermisionUserController::class, 'permissionsUser']);

    // Users routes
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    // Resources and Permissions routes
    Route::get('/resources', [ResourceController::class, 'index']);
});

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to API']);
});
