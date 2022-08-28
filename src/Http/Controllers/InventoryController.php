<?php

namespace BambanetLms\Inventory\Http\Controllers;

use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class InventoryController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('Title')
            ->description('Description')
            ->body(view('inventory::index'));
    }
}