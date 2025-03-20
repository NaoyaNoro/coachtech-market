<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sell;
use App\Http\Requests\AddressRequest;

class UserController extends Controller
{
    public function profileSetting()
    {
        $user = User::with('profile')->find(auth()->id());
        return view('profile_setting', compact('user'));
    }

    public function updateProfile(AddressRequest $request)
    {
        $profile = Profile::firstOrNew(['user_id' => auth()->id()]);
        // プロフィール画像の保存処理
        if ($request->hasFile('image')) {
            // 保存先のディレクトリを指定
            $destinationPath = storage_path('app/public/img/profile');

            // オリジナルのファイル名を取得
            $filename = $request->file('image')->getClientOriginalName();

            // ファイルを保存
            $request->file('image')->move($destinationPath, $filename);

            $profile->image = $filename;
        }

        if (!$request->hasFile('image') && $profile->exists) {
            $filename = $profile->image;
        }

        // プロフィールを更新または作成
        Profile::updateOrCreate(
            ['user_id' => auth()->id()], // 検索条件（ログイン中のユーザーID）
            [
                'image' => $filename, // ファイル名のみ保存
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        // フラッシュメッセージを設定してリダイレクト
        return redirect('/');
    }

    public function profile(Request $request)
    {
        $user_id = auth()->id();
        $user = User::with('profile')->find(auth()->id());

        $mySellIds = Sell::where('user_id', $user_id)->pluck('product_id')->toArray();

        $myPurchaseIds = Purchase::where('user_id', $user_id)->pluck('product_id')->toArray();

        $mySellProducts = Product::whereIn('id', $mySellIds)->select('name', 'image')->get();

        $myPurchaseProducts = Product::whereIn('id', $myPurchaseIds)->select('name', 'image')->get();

        $activeTab=$request->query('tab','sell');
        return view('profile ', compact('user', 'mySellProducts', 'myPurchaseProducts','activeTab'));
    }
}
