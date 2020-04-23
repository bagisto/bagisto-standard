<?php

namespace Webkul\UpgradeVersion\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.content.before', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('upgradeversion::notification.index');
        });

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('upgradeversion::layouts.style');
        });
    }
}