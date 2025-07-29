<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sell;
use App\Models\Transaction;
use App\Models\Chat;
use App\Models\Value;
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
            ['user_id' => auth()->id()],
            [
                'image' => $filename,
                'post_code' => $request->post_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );
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


        $myTransactionProducts = Transaction::with('product:id,name,image')
            ->withCount([
                'chats as unread_chats_count'=>function($query) use ($user_id){
                    $query->where('is_read', false)
                    ->where('sender_id', '!=', $user_id);
                }
            ])
            ->where('buyer_id', $user_id)
            ->orWhere('seller_id', $user_id)
            ->get(['id', 'product_id']);

        $activeTab=$request->query('tab','sell');

        $transactionIds=Transaction::where('seller_id',$user_id)
        ->orWhere('buyer_id',$user_id)
        ->pluck('id');

        $completeTransactionIds=Value::where('sender_id',$user_id)
        ->pluck('transaction_id')
        ->toArray();


        $sumUnreadChats=Chat::whereIn('transaction_id',$transactionIds)->where('sender_id', '!=', $user_id)
        ->where('is_read', false)
        ->count();

        $values=Value::where('receiver_id',$user_id )
        ->get();
        $countValue=count($values);
        $sumValue=0;
        foreach ($values as $value){
            $sumValue=$sumValue+$value->value;
        }
        if($countValue==0){
            $avValue=0;
        }else{
            $avValue = round($sumValue / $countValue);
        }
        
        return view('profile ', compact('user', 'mySellProducts', 'myPurchaseProducts', 'myTransactionProducts','activeTab', 'sumUnreadChats',"avValue", "completeTransactionIds"));
    }
}
