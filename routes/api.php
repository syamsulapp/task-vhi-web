<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\PhotosController;
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


Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [Auth::class, 'login']);
        Route::post('register', [Auth::class, 'register']);
        Route::middleware('auth:api')->group(function () {
            Route::post('profile', [Auth::class, 'profile']);
            Route::post('profile/update', [Auth::class, 'updateProfile']);
            Route::post('logout', [Auth::class, 'logout']);
        });
    });
    Route::prefix('photos')->group(function () {
        Route::get('', [PhotosController::class, 'index']);
        Route::get('{id}', [PhotosController::class, 'detail']);
        Route::middleware('user:api')->group(function () {
            Route::post('', [PhotosController::class, 'store']);
            Route::put('{id}', [PhotosController::class, 'update']);
            Route::delete('{id}', [PhotosController::class, 'destroy']);
            Route::post('{id}/like', [PhotosController::class, 'like']);
            Route::post('{id}/unlike', [PhotosController::class, 'unlike']);
        });
    });
});
