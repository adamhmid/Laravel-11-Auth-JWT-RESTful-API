<?php

namespace App\Providers;

use App\Auth\Guards\JWTGuard;
use App\Services\JWTAuthService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
    RateLimiter::for('login', function (Request $request) {
      return Limit::perMinute(5, 5)->by($request->ip());
    });

    RateLimiter::for('api', function (Request $request) {
      return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    // Daftarkan guard jwt
    Auth::extend('jwt', function (Application $app, string $name, array $config) {
      return new JWTGuard(
        new JWTAuthService(),
        $app['request'],
        Auth::createUserProvider($config['provider'])
      );
    });
  }
}
