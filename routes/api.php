<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\GrievanceController;

Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('user-info', [AuthController::class, 'userInfo'])->name('user.info');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Group for user role
    Route::middleware('role:user')->group(function () {
        Route::get('grievances', [GrievanceController::class, 'index'])->name('grievances.index');
        Route::post('grievances', [GrievanceController::class, 'store'])->name('grievances.store');
        Route::get('grievances/{id}/messages', [GrievanceController::class, 'messages'])->name('grievances.messages');
        Route::post('grievances/{grievance_id}/messages', [MessageController::class, 'store'])->name('messages.store');
    });

    // Group for admin role
    Route::middleware('role:admin')->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('surveys', [SurveyController::class, 'index'])->name('surveys.index');
        Route::post('surveys', [SurveyController::class, 'store'])->name('surveys.store');
        Route::get('surveys/{id}', [SurveyController::class, 'show'])->name('surveys.show');
        Route::put('surveys/{id}', [SurveyController::class, 'update'])->name('surveys.update');
        Route::delete('surveys/{id}', [SurveyController::class, 'destroy'])->name('surveys.delete');
        Route::put('surveys/{id}/update-status-and-survey', [SurveyController::class, 'updateStatusAndSurvey'])->name('surveys.updateStatusAndSurvey');

        Route::get('grievances/all', [GrievanceController::class, 'allGrievances'])->name('grievances.all');
        Route::put('grievances/{id}/close', [GrievanceController::class, 'close'])->name('grievances.close');
        Route::get('grievances/{id}/messages', [GrievanceController::class, 'messages'])->name('grievances.messages.admin');
        
        // Route for articles
        Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
        Route::post('articles', [ArticleController::class, 'store'])->name('articles.store');
        Route::get('articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
        Route::put('articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });
});
