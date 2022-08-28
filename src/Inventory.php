<?php

namespace BambanetLms\Inventory;

use Encore\Admin\Extension;


class Inventory extends Extension
{

    use BootExtension;
    
    //public $name = 'inventory';

    //public $views = __DIR__.'/../resources/views';

    //public $assets = __DIR__.'/../resources/assets';

    /**public $menu = [
        'title' => 'Suppliers',
        'path'  => 'bam-item-suppliers',
        'icon'  => 'fa-users',
    ];**/

    /**
     * {@inheritdoc}
     */

    public static function import(){
        $submenu = array (
            array('title'=>'Issue','path'=>'bam-item-stock-issues','icon'=>'fa-users'),
            array('title'=>'Item Stock','path'=>'bam-item-stocks','icon'=>'fa-arrow-right'),
            array('title'=>'Item','path'=>'bam-items','icon'=>'fa-arrow-right'),
            array('title'=>'Item Category','path'=>'bam-item-categories','icon'=>'fa-arrow-right'),
            array('title'=>'Store','path'=>'bam-item-stores','icon'=>'fa-arrow-right'),
            array('title'=>'Supplier','path'=>'bam-item-suppliers','icon'=>'fa-arrow-right'),
        );
        parent::createMenu('Inventory','/','fa-shopping-bag',0,$submenu);
            
            //parent::createPermission('Media manager', 'ext.media-manager', 'media*');
        
    }

}