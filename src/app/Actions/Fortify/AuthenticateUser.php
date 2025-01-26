<?php

namespace App\Actions\Fortify;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    /**
     * Handle the login process.
     *
     * @param  array  $input
     * @return \App\Models\User|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(array $input)
    {
        // LoginRequestを解決してバリデーションを実行
        $request = app(LoginRequest::class);
        $request->replace($input); // 配列データをリクエストとしてマージ
        $validated = $request->validated(); // バリデーション済みデータを取得

        // 認証処理
        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ], $request->boolean('remember'))) {
            return Auth::user(); // 認証成功時にユーザーを返す
        }

        // 認証失敗時のエラーをスロー
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
