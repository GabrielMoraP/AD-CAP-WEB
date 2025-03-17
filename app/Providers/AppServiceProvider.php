<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

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
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
        Request::macro('hasValidSignature', function ($absolute = true) {
            if (config('app.env') === 'local') return true;
            return URL::hasValidSignature($this, $absolute);
        
        });
        Request::macro('hasValidRelativeSignature', function () {
            if (config('app.env') === 'local') return true;
            return URL::hasValidSignature($this, $absolute = false);
        });
        Request::macro('hasValidSignatureWhileIgnoring', function ($ignoreQuery = [], $absolute = true) {
            if (config('app.env') === 'local') return true;
            return URL::hasValidSignature($this, $absolute, $ignoreQuery);
        });
    }
}
