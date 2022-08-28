<?php

namespace BambanetLms\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use DB;
use PDF;
use Encore\Admin\Widgets\Table;
use Jxlwqq\DataTable\DataTable;
use Illuminate\Http\Request;
use App\Models\BamAcademicAttendance;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Box;
use Session;
use BambanetLms\Inventory\Models\BamAcademicStudents;
use BambanetLms\Inventory\Models\Bam_ItemStock;
use BambanetLms\Inventory\Models\Bam_ItemStockIssue;


class CustomStockController extends Controller
{
    public function registerStock(Request $request){
        if($request->document!= ''){
            $fileName = time().'.'.$request->document->extension();
            $file = $request->document->move(public_path('uploads/images'), $fileName);
            $fileName = 'images/'.$fileName;
        }else{
            $fileName = '';
        }

        $up_stock = DB::table('bam__items')->where('id',$request->item)->first();
        $up_stock_re = $up_stock->unit + $request['quantity'];
        DB::table('bam__items')->where('id',$request->item)->update([
            'unit' =>$up_stock_re
        ]);


        Bam_ItemStock::create([
            'item' => $request['item'],
            'item_category' => $request['item_category'],
            'supplier_id' => $request['supplier_id'],
            'store_id' => $request['store_id'],
            'quantity' => $request['quantity'],
            'purchase_price' => $request['purchase_price'],
            'document' => $fileName,
            'description' => $request['description'],
            'added_by' => $request['added_by'],
            'school_id' => $request['school_id'],
        ]);
        admin_toastr('Item Stock Added', 'success');
        return redirect()->back();
    }
    public function issueStock(Request $request)
    {
        Bam_ItemStockIssue::create($request->all());

        admin_toastr('Item Issued Successfully', 'success');
        return redirect()->back();
    }
}