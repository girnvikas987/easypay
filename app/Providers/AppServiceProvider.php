<?php

namespace App\Providers;

 
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

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
        ini_set('memory_limit', '1G'); // Adjust as needed
        set_time_limit(600); // 300 seconds
        Paginator::useBootstrapFive();
          
        if($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
