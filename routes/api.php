<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\ProductCategoryController;
use \App\Http\Controllers\Api\ProductController;
use \App\Http\Controllers\Api\UserController;
use \App\Http\Controllers\Api\ShopController;
use \App\Http\Controllers\Api\BannerController;
use \App\Http\Controllers\Api\AuthController;

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
    Route::get('activeCategories', [ProductCategoryController::class, 'activeCategories']);
    Route::get('banners', [BannerController::class, 'getBanners']);
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

    Route::group([
        'prefix' => 'favourites'
    ], function () {
        Route::get('/', [ProductController::class, 'getFavourites']);
        Route::post('/add', [ProductController::class, 'addToFavourite']);
        Route::post('/remove', [ProductController::class, 'removeFromFavourites']);
    });

});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('confirm', [AuthController::class, 'confirm']);
Route::group(['prefix' => 'recovery'], function () {
    Route::post('', [AuthController::class, 'resetPassword']);
    Route::post('setPassword', [AuthController::class, 'setPassword'])->middleware(['auth:sanctum']);
});
