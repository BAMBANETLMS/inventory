<?php

namespace BambanetLms\Inventory;

use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    //Inventory $extension
    public function boot()
    {
         Inventory::boot();
        /**if (!Inventory::boot()) {
            return ;
        }**/

        /**if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'inventory');
        }
        
        if ($this->app->runningInConsole()) {
            // 数据库迁移
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/bambanetlms/inventory')],
                'inventory'
            );
        }

        $this->app->booted(function () {
            Inventory::routes(__DIR__.'/../routes/web.php');
           
        });**/
    }
}