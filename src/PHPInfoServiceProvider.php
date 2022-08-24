<?php

namespace Encore\PHPInfo;

use Illuminate\Support\ServiceProvider;

class PHPInfoServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(PHPInfo $extension)
    {
        if (! PHPInfo::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'inventory');
        }

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/heinrich/inventory')],
                'inventory'
            );
        }

        $this->app->booted(function () {
            PHPInfo::routes(__DIR__.'/../routes/web.php');
        });
    }
}