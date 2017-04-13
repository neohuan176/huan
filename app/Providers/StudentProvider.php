<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use App\Student\StudentServices;

class StudentProvider extends ServiceProvider
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
        $this->app->bind('StudentServ', function()
        {
            $test = new StudentServices();
            Log::info("进入register");
            return new StudentServices();
        });
    }
}
