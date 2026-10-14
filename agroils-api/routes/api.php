<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('v1/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


require __DIR__ . '/../app/Modules/Users/Routes/api.php';
