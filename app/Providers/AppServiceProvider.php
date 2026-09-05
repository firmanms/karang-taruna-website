<?php

namespace App\Providers;

use App\Domain\Settings\Models\SiteVisitorLog;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.public', function ($view) {
            $view->with('visitorStats', SiteVisitorLog::getVisitorStats());
        });
    }
}
