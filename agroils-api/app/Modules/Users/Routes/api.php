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

Route::prefix('v1/user')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/',[UserController::class,'index']);
    Route::post('{creator_uuid}/create/',[UserController::class,'create']);
});

/**
 *  protected with sanctum
 *  otp verification
 *
 */

Route::prefix('v1/verify-token/')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/otp',[UserController::class,'verifyToken']);
});
