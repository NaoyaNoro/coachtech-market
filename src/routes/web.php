<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;



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
    Route::get('/mypage/profile', [UserController::class, 'profile']);
    Route::post('/mypage/profile', [UserController::class, 'updateProfile']);
    Route::post('/comment', [ProductController::class, 'comment']);
    Route::post('/good_button', [ProductController::class, 'good']);
});






