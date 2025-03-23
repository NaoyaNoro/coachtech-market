<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Purchase;
use App\Models\Profile;
use App\Http\Requests\PurchaseRequest;


class StripePaymentController extends Controller
{
    public function checkout(PurchaseRequest $request)
    {
        // 環境変数からAPIキーを設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // 購入する商品の情報
        $productName = $request->product_name;
        $productPrice = $request->product_price;
        $paymentMethod = $request->purchase__method;

        if($paymentMethod==='カード支払い'){
            $payment_method_types = ['card'];
        }elseif($paymentMethod==='コンビニ払い'){
            $payment_method_types = ['konbini'];
        }else{
            return redirect()->back()->with('error', '無効な支払い方法です');
        }

        // Stripe セッションを作成
        $session = Session::create([
            'payment_method_types' =>
            $payment_method_types,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $productName,
                    ],
                    'unit_amount' => $productPrice ,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/payment/success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => url('/payment/cancel'),
            'metadata'=>[
                'product_id'=>$request->product_id
            ]
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // セッションIDを取得
        $session_id = $request->query('session_id');
        if (!$session_id) {
            return redirect('/')->with('error', '決済情報が見つかりません');
        }

        $session = Session::retrieve($session_id);

        $user = auth()->user();
        $profile = Profile::where('user_id', $user->id)->first();

        $purchase=[
            'product_id' => $session->metadata->product_id,
            'user_id'=>auth()->id(),
            'post_code'=>$profile->post_code,
            'address'=>$profile->address,
            'building'=>$profile->building,
        ];
        Purchase::create($purchase);
        return view('thanks');
    }
}
