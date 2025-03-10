<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostCategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\BookmarkController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);;

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail']);

    Route::apiResource('categories', PostCategoryController::class);
    Route::delete('categories/{id}', [PostCategoryController::class, 'destroy']);
    Route::apiResource('posts', PostController::class);
    Route::apiResource('bookmarks', BookmarkController::class);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
