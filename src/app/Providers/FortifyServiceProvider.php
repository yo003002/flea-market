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
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
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
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
        
        $this->app->bind(FortifyLoginRequest::class, LoginRequest::class);



        //登録後初回プロフィールへ
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    return redirect('/mypage/profile');
                }
            };
        });

        //プロフィール未設定ならプロフィールへ
        // $this->app->singleton(LoginResponse::class, function () {
        //     return new class implements LoginResponse {
        //         public function toResponse($request)
        //         {
        //             $user = $request->user();

        //             if (!$user->is_profile_set) {
        //                 return redirect('/mypage/profile');
        //             }

        //             return redirect('/');
        //         }
        //     };
        // });



        // メール認証機能
        // $this->app->singleton(LoginResponse::class, function () {
        //     return new class implements LoginResponse {
                
        //         public function toResponse($request)
        //         {
        //             $user = $request->user();

        //             // メール最優先
        //             if (! $user->hasVerifiedEmail()) {
        //                 return redirect('/email/verify');
        //             }

        //             // プロフィール未設定
        //             if (! $user->is_profile_set) {
        //                 return redirect('/mypage/profile');
        //             }

        //             return redirect('/');
        //         }
        //     };
        // });

    }

}
