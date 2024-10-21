<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DocumentController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user-info', [AuthController::class, 'userInfo']);
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('role:user')->group(function () {
        Route::get('documents', [DocumentController::class, 'index']);
        Route::post('documents', [DocumentController::class, 'store']);
        Route::get('documents/{id}', [DocumentController::class, 'show']);
        Route::put('documents/{id}', [DocumentController::class, 'update']);
        Route::delete('documents/{id}', [DocumentController::class, 'destroy']);

        Route::get('profile', [UserController::class, 'show']);  // Récupérer le profil de l'utilisateur connecté
        Route::put('profile', [UserController::class, 'update']);  // Mettre à jour le profil de l'utilisateur connecté
    });

    Route::middleware('role:admin')->group(function () {
        Route::delete('users/{id}', [UserController::class, 'destroy']);    
    });
});
