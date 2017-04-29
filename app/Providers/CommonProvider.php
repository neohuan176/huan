<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Acme\CommonServices;

class CommonProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('CommonServ', function()
        {
            return new CommonServices();
        });
    }
}
