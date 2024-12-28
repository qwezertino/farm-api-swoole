<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index']);

Route::apiResource('products', ProductController::class)->except(['index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
