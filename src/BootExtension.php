<?php

namespace BambanetLms\Inventory;
use Encore\Admin\Admin;
use BambanetLms\Inventory\Http\Controllers\Bam_ItemSupplierController;
use BambanetLms\Inventory\Http\Controllers\Bam_ItemStoreController;
use BambanetLms\Inventory\Http\Controllers\Bam_ItemCategoryController;
use BambanetLms\Inventory\Http\Controllers\Bam_ItemController;
use BambanetLms\Inventory\Http\Controllers\Bam_ItemStockController;
use BambanetLms\Inventory\Http\Controllers\Bam_ItemStockIssueController;
use BambanetLms\Inventory\Http\Controllers\StockAjaxController;
use BambanetLms\Inventory\Http\Controllers\CustomStockController;

trait BootExtension 
{
    public static function boot()
    {
        static::registerRoutes();

        Admin::extend('inventory', __CLASS__);
    }

    protected static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->resource('bam-item-suppliers', Bam_ItemSupplierController::class);
            //Store
            $router->resource('bam-item-stores', Bam_ItemStoreController::class);
            //Category
            $router->resource('bam-item-categories', Bam_ItemCategoryController::class);
            //item
            $router->resource('bam-items', Bam_ItemController::class);
            //Item Stock
            $router->resource('bam-item-stocks', Bam_ItemStockController::class);
            //Register Stock
            $router->post('/register-stock', [CustomStockController::class,'registerStock']);
            //Issue Stock
            $router->resource('bam-item-stock-issues', Bam_ItemStockIssueController::class);
            //Issue Stock
            $router->post('/issue-stock', [CustomStockController::class,'issueStock']);
            //ajaxRequest
            $router->any('/stockajaxrequest', [StockAjaxController::class,'ajaxRequest']);

            
        });
    }

    
}