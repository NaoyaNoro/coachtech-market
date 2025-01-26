<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Actions\Fortify\AuthenticateUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        /*
        Fortify::createUsersUsing(CreateNewUser::class);
        */
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);


        Fortify::registerView(function(){
            return view('register');
        });

        /*
        Fortify::authenticateUsing(function (\Illuminate\Http\Request $request) {
            Log::info('Custom authenticateUsing is called'); 
            $action = app(\App\Actions\Fortify\AuthenticateUser::class);

            return $action->authenticate($request->only(['email', 'password', 'remember']));
        });
        */


        Fortify::loginView(function(){
            return view('login');
        });

        RateLimiter::for('login',function (Request $request){
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });

        Event::listen(Registered::class, function ($event) {
            session(['url.intended' => '/mypage/profile']);
        });
    }
}
