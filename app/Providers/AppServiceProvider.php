<?php

namespace App\Providers;

use Illuminate\Foundation\DevCommands;
use Illuminate\Support\Facades\URL;
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
        DevCommands::except('vite');

        // Some tunnels terminate TLS but forward the request as plain http without
        // an X-Forwarded-Proto header. Without this every asset URL comes out as
        // http:// and the browser blocks it as mixed content on an https page.
        if (config('app.force_https')) {
            URL::forceScheme('https');
        }
    }
}
