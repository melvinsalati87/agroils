<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Users\Controllers\UserController;

Route::prefix('v1')->group(function () {
    Route::post('/auth',[UserController::class,'login']);
    Route::post('/register',[UserController::class,'register']);
    Route::post('/logout',[UserController::class,'logout']);
    Route::post('/refresh',[UserController::class,'refresh']);
});

/**
 * Protected  with sanctum  middlware
 **
 */

Route::prefix('v1')->group(function () {
    Route::get('/users',[UserController::class,'index']);
})->middleware(['auth:sanctum']);
