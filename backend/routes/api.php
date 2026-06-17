<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PostController; // Added: PostController import
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

Route::prefix('v1')->group(function () {
    // Public routes for authentication
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });

    // Protected routes requiring a valid JWT Token
    Route::middleware('auth:api')->group(function () {
        // Auth endpoints
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::get('auth/me', [AuthController::class, 'me']);

        // Posts CRUD endpoints (Added in Sprint 2)
        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/{id}', [PostController::class, 'show'])->name('posts.show');
        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::put('posts/{id}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    });
});
