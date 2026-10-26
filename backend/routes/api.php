<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CatalogController;
use App\Http\Controllers\Api\TelaController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ComboController;
use App\Http\Controllers\Api\DesignController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\OrderController;

Route::get('/catalog', [CatalogController::class, 'index']);
Route::apiResource('telas', TelaController::class)->only(['index','store','show','update','destroy']);
Route::delete('telas/{id}/photos', [TelaController::class, 'destroyPhoto']);

Route::apiResource('users', UserController::class)->only(['index','store','update','destroy']);
Route::apiResource('categories', CategoryController::class)->only(['index','store','update','destroy']);
Route::apiResource('products', ProductController::class)->only(['index','store','show','update','destroy']);
Route::apiResource('combos', ComboController::class)->only(['index','store','update','destroy']);
Route::apiResource('designs', DesignController::class)->only(['index','store','destroy']);
Route::apiResource('clients', ClientController::class)->only(['index','store','destroy']);
Route::apiResource('orders', OrderController::class)->only(['index','store','update','destroy']);