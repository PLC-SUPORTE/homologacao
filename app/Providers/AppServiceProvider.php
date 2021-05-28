<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
            }
        
            if($this->app->environment('local')) {
            \URL::forceScheme('http');
            }
        
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
