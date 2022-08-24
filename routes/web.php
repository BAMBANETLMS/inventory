<?php

use Encore\PHPInfo\Http\Controllers\PHPInfoController;
use Encore\PHPInfo\Http\Controllers\Bam_ItemSupplierController;
use Encore\PHPInfo\Http\Controllers\Bam_ItemStoreController;
use Encore\PHPInfo\Http\Controllers\Bam_ItemCategoryController;
use Encore\PHPInfo\Http\Controllers\Bam_ItemController;
use Encore\PHPInfo\Http\Controllers\Bam_ItemStockController;
use Encore\PHPInfo\Http\Controllers\Bam_ItemStockIssueController;
use Encore\PHPInfo\Http\Controllers\StockAjaxController;
use Encore\PHPInfo\Http\Controllers\CustomStockController;
//use Illuminate\Routing\Router;

//Route::get('inventory', PHPInfoController::class.'@index');




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
