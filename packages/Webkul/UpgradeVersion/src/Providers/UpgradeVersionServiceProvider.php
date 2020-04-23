<?php

namespace Webkul\UpgradeVersion\Providers;

use Illuminate\Support\ServiceProvider;

class UpgradeVersionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'upgradeversion');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/upgrade-version/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'upgradeversion');

        $this->app->register(EventServiceProvider::class);
    }
}