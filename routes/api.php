<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DocumentController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'abilities:issue-access-token'])->group(function () {
    Route::post('/auth/refresh-token', [AuthController::class, 'refreshToken']); // Modifier en POST
});

Route::middleware(['auth:sanctum', 'abilities:access-api'])->group(function () {
    Route::get('documents', [DocumentController::class, 'index']);
    Route::post('documents', [DocumentController::class, 'store']);
    Route::get('documents/{id}', [DocumentController::class, 'show']);
    Route::put('documents/{id}', [DocumentController::class, 'update']);
    Route::delete('documents/{id}', [DocumentController::class, 'destroy']);
});
