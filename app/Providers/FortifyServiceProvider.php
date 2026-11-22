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
use Laravel\Fortify\Fortify;
use Inertia\Inertia;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // login
        Fortify::loginView(function() {
            return Inertia::render('Auth/Login');
        });

        Fortify::authenticateUsing(function (Request $request) {
            $turnstileToken = $request->input('cf-turnstile-response') ?? $request->input('turnstile_token');
            if (!validateTurnstile($turnstileToken, $request->ip())) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'turnstile' => ['Verifikasi keamanan Cloudflare Turnstile gagal. Silakan coba lagi.']
                ]);
            }

            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                return $user;
            }

            return null;
        });

        // register
        Fortify::registerView(function() {
            return Inertia::render('Auth/Register');
        });

        /**
         * login & logout responses
         */
        $this->app->singleton(\Laravel\Fortify\Contracts\LoginResponse::class, \App\Http\Responses\LoginResponse::class);
        $this->app->singleton(\Laravel\Fortify\Contracts\LogoutResponse::class, \App\Http\Responses\LogoutResponse::class);
    }
}
