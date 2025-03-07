<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use App\Http\Requests\LoginRequest;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🔹 Fortify にカスタムユーザー登録処理を設定
        Fortify::createUsersUsing(CreateNewUser::class);

        // 🔹 会員登録後のリダイレクト先 → まずはメール認証ページへ
        Event::listen(Registered::class, function ($event) {
            session(['url.intended' => route('verification.notice')]); // 🛠 認証ページへ
        });

        // 🔹 Fortify のデフォルトの登録 & ログインビュー
        Fortify::registerView(fn() => view('register'));
        Fortify::loginView(fn() => view('login'));

        // 🔹 ログイン時の処理
        Fortify::authenticateUsing(function ($request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return null; // 認証失敗時
            }

            // 🔹 未認証ユーザーはログインを拒否（エラーメッセージを表示）
            if (!$user->hasVerifiedEmail()) {
                return null; // Fortify のデフォルトの処理で「認証情報が正しくありません」と表示される
            }

            return $user; // 認証成功時
        });

        // 🔹 ログインのレート制限
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);
    }
}
