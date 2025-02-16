<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SellController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ProductController::class, 'index']);
Route::post('/search',[ProductController::class, 'search']);

Route::get('/item/{item_id}', [ProductController::class, 'detail']);

Route::middleware('auth')->group(function(){
    Route::get('/mypage/profile', [UserController::class, 'profileSetting']);
    Route::post('/mypage/profile', [UserController::class, 'updateProfile']);
    Route::get('/mypage', [UserController::class, 'profile']);
    Route::post('/comment', [ProductController::class, 'comment']);
    Route::post('/good_button', [ProductController::class, 'good']);
});

Route::middleware('auth')->group(function(){
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    Route::post('/purchase/address/{user_id}', [PurchaseController::class, 'address']);
    Route::post('/change/address', [PurchaseController::class, 'change_address']);

});

Route::middleware('auth')->group(function(){
    Route::post('/checkout',[StripePaymentController::class,'checkout']);
    Route::get('/payment/success',[StripePaymentController::class,'success']);
    Route::get('/payment/cancel', [StripePaymentController::class, 'cancel']);
});

Route::middleware('auth')->group(function(){
    Route::get('/sell',[SellController::class,'sell']);
    Route::post('/sell', [SellController::class, 'put_up']);
});







