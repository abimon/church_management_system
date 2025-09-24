<?php

use App\Http\Controllers\ChurchController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::resources([
    'users' => UserController::class,
    'church'=>ChurchController::class,
]);