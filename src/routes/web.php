<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ValueController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;



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
Route::post('/search', [ProductController::class, 'search']);
Route::get('/item/{item_id}', [ProductController::class, 'detail']);

// 認証必須 (ただしメール認証不要のルート)
Route::middleware('auth')->group(function () {
    // メール認証待ちページ (未認証でもアクセス可能)
    Route::get('/email/verify', function () {
        return view('auth.verify_email');
    })->name('verification.notice');

    // メール認証処理
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/mypage/profile');
    })->middleware(['signed'])->name('verification.verify');

    // 認証メールの再送信
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '確認メールを再送信しました。');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

//  認証 & メール認証済みユーザーのみアクセス可能
Route::middleware(['auth', 'verified'])->group(function () {
    //  プロフィール関連
    Route::get('/mypage/profile', [UserController::class, 'profileSetting']);
    Route::post('/mypage/profile', [UserController::class, 'updateProfile']);
    Route::get('/mypage', [UserController::class, 'profile']);

    //  商品関連
    Route::post('/comment', [ProductController::class, 'comment']);
    Route::post('/good_button', [ProductController::class, 'good']);

    //  購入関連
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'purchase']);
    Route::post('/change/address', [PurchaseController::class, 'change_address']);
    Route::post('/purchase/address/{user_id}', [PurchaseController::class, 'address']);
    Route::get('/purchase/address/{user_id}', [PurchaseController::class, 'redirect_change_address']);

    //  支払い関連
    Route::post('/checkout', [StripePaymentController::class, 'checkout']);
    Route::get('/payment/success', [StripePaymentController::class, 'success']);

    //  出品関連
    Route::get('/sell', [SellController::class, 'sell']);
    Route::post('/sell', [SellController::class, 'put_up']);

    //chat機能
    Route::get('/chat/{transaction_id}', [ChatController::class, 'transaction']);

    //chat送信
    Route::post('/send/chat', [ChatController::class, 'send_chat']);

    // Route::patch('/chat/edit/{chat_id}', [ChatController::class, 'edit_chat']);

    // Route::delete('/chat/delete/{chat_id}', [ChatController::class, 'delete_chat']);

    //chatの編集
    Route::post('/chat/action/{chat_id}', [ChatController::class, 'chat_edit']);

    //評価の送信
    Route::post('/value', [ValueController::class, 'send_value']);

    //取引へ
    Route::post('/make/transaction', [ChatController::class, 'make_transaction']);
});

