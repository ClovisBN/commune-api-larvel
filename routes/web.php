<?php

use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
