<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(UserController::class)->prefix('user')->group(function () {
    Route::post('/register', 'store');
    Route::post('/login', 'create');
    Route::post('/logout', 'logout');
    Route::post('/refresh', 'refresh');
    Route::get('/profile', 'profile')->middleware('auth:sanctum');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'delete');
});

Route::controller(ChurchController::class)->prefix('/church')->group(function () {
    Route::get('/', 'index');
    Route::get('/show/{id}', 'show');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'delete');
});
Route::controller(AccountController::class)->prefix('/account')->group(function () {
    Route::get('/', 'index');
    Route::get('/show/{id}', 'show')->middleware('auth:sanctum');
    Route::post('/store', 'store')->middleware('auth:sanctum');
    Route::put('/update/{id}', 'update')->middleware('auth:sanctum');
    Route::delete('/delete/{id}', 'delete')->middleware('auth:sanctum');
    Route::get('/summary', 'summary')->middleware('auth:sanctum');
    Route::get('/active', 'active')->middleware('auth:sanctum');
});
Route::controller(PaymentController::class)->middleware('auth:sanctum')->prefix('/payment')->group(function () {
    Route::get('/', 'index');
    Route::get('/show/{id}', 'show');
    Route::post('/store', 'store');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'delete');
});