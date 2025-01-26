<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        return view('profile_setting', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // プロフィール画像の保存処理
        if ($request->hasFile('image')) {
            // 保存先のディレクトリを指定
            $destinationPath = storage_path('app/public/img/profile');

            // オリジナルのファイル名を取得
            $filename = $request->file('image')->getClientOriginalName();

            // ファイルを保存
            $request->file('image')->move($destinationPath, $filename);
        } else {
            // 元の画像名を保持または空にする（デフォルト）
            $filename = null;
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
}
