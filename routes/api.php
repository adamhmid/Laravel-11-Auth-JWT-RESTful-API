<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
  Route::post('login', [AuthController::class, 'login'])->middleware('throttle:login');
  Route::post('refresh', [AuthController::class, 'refresh'])->middleware('throttle:api');
  Route::post('logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::prefix('v1')->middleware(['throttle:api', 'auth'])->group(function () {
  Route::get('profile', [ProfileController::class, 'show']);
  Route::put('profile', [ProfileController::class, 'update']);
  Route::apiResource('users', UserController::class);
  Route::apiResource('products', ProductController::class);
});
