<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                $guard = 'web';
                if(Auth::guard('admin')->check()) $guard = 'admin';

                Auth::guard($guard)->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                if($guard === 'admin') return redirect('/admin/login');

                return redirect('/login');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot() : void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::verifyEmailView(function(){
            return view('auth.verify');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
