<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Auth\EloquentUserProvider;
//use App\CustomGuard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //为了在模板添加判断teacher
//        Auth::extend('eloquent.teacher', function ($app) {
//            $model = $app['config']['auth.model'];
//            $provider = new EloquentUserProvider($app['hash'], $model);
//            return new CustomGuard($provider, \App::make('session.store'));
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
