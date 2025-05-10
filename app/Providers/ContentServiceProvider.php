<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Infrastructure\ContentProviders\ContentProviderInterface;
use App\Infrastructure\ContentProviders\StaticContentProvider;

class ContentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ContentProviderInterface::class, function ($app) {
            return new StaticContentProvider();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
