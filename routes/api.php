<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post("/login", 'login');
    Route::middleware("auth:sanctum")->post("/logout", 'logout');
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
