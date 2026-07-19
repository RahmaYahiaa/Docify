<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OctaneServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Define signal constants for Windows compatibility
        if (! defined('SIGINT')) {
            define('SIGINT', 2);
        }
        if (! defined('SIGTERM')) {
            define('SIGTERM', 15);
        }
        if (! defined('SIGHUP')) {
            define('SIGHUP', 1);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
