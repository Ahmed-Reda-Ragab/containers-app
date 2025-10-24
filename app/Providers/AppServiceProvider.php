<?php

namespace App\Providers;

use App\Core\Theme;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Theme::class, function ($app) {
            return new Theme();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $helperPath = app_path('Core/theme_helper.php');
        if (file_exists($helperPath)) {
            require_once $helperPath;
        }

    }
}
