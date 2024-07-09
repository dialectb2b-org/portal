<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use SendGrid;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('App\Services\SendGridEmailService', function ($app) {
            return new \App\Services\SendGridEmailService(
                new SendGrid(env('SENDGRID_API_KEY'))
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
