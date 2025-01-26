<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\LogoutResponse;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as BaseController;

class CustomAuthenticatedSessionController extends BaseController
{
    /**
     * Handle the login request.
     */
    public function store(Request $request): LoginResponse
    {
        // LoginRequestをインスタンス化してリクエストデータを渡す
        $loginRequest = LoginRequest::createFrom($request);

        // バリデーションを実行
        $validatedData = $loginRequest->validated();

        // 認証処理
        if (!Auth::attempt([
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // セッションを再生成
        $request->session()->regenerate();

        return app(LoginResponse::class);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): LogoutResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return app(LogoutResponse::class);
    }
}
