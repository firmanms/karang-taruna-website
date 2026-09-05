<?php

namespace App\Providers;

use App\Domain\Settings\Models\SiteVisitorLog;
use Filament\Forms\Components\FileUpload;
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

        // Konfigurasi Global Upload File: Maksimal 512 KB dengan pemberitahuan
        FileUpload::configureUsing(function (FileUpload $fileUpload) {
            $fileUpload
                ->maxSize(512)
                ->helperText('Ukuran maksimal file yang diunggah adalah 512 KB.');
        });
    }
}
