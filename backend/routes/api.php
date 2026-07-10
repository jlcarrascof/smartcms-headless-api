<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
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

        // Categories Reading — Available for admin, editor, and viewer roles
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

        // Posts Reading — Available for admin, editor, and viewer roles
        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/{id}', [PostController::class, 'show'])->name('posts.show');

        // Posts & Categories Writing — Only accessible by admin and editor roles
        Route::middleware('role:admin,editor')->group(function () {
            Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
            Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

            Route::post('posts', [PostController::class, 'store'])->name('posts.store');
            Route::put('posts/{id}', [PostController::class, 'update'])->name('posts.update');
        });

        // Posts & Categories Deletion — Only accessible by admin role
        Route::middleware('role:admin')->group(function () {
            Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
            Route::delete('posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
        });

    });
});
