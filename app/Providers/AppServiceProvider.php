<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       //$this->app->singleton(\App\Services\OpenAIService::class);
    //    $this->app->singleton(DeepSeekService::class, function ($app) {
    //         return new DeepSeekService();
    //     });
        $this->app->singleton(\App\Services\DeepSeekService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
