<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Acme\TeacherServices;

class TeacherProvider extends ServiceProvider
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
        $this->app->bind('TeacherServ', function()
        {
            return new TeacherServices();
        });
    }
}
