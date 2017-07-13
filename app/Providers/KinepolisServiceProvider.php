<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Theatres\Kinepolis;

class KinepolisServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('Kinepolis', function () {
            return new Kinepolis();
        });
    }
}
