<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ProductCategoryController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\ShopController;


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


Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::post('category', [ProductCategoryController::class, 'getCategories']);
    Route::post('products', [ProductController::class, 'getProducts']);
    Route::post('productByCode', [ProductController::class, 'productByCode']);
    Route::get('cities', [ShopController::class, 'getCities']);
    Route::get('shops', [ShopController::class, 'index']);
    Route::group([
        'prefix' => 'profile'
    ], function () {
        Route::get('/', [UserController::class, 'profile']);
        Route::patch('/', [UserController::class, 'update']);
    });

});

Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('confirm', [\App\Http\Controllers\Api\AuthController::class, 'confirm']);
