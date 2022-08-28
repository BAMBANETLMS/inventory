<?php

namespace BambanetLms\Inventory\Actions\Item;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;

class ItemStock extends Action
{
    //protected $selector = '.item-stock';

    public function handle(Request $request)
    {
        // $request ...

        return $this->response()->success('Success message...')->refresh();
    }

    protected function script()
    {

        return <<<SCRIPT
         
            $('#item_category').on('change', function () {
                $.ajax({
                    type: "GET",
                    url: $('#current_url').val()+"/admin/stockajaxrequest",
                    data: {f: 'stock',s:'getitems',id:$('#item_category').val()},
                    success: function(data){
                        console.log(data);
                        if (data['errors']) {
                            Swal.fire(
                                'Error',
                                'An error occured: Fill All Required Fields',
                                'error'
                            );
                        }else if(data['status'] == 200){
                            $('#item').html(data.message);
                            
                        }else if(data['status'] == 400){
                            Swal.fire(
                                'Ooops!..',
                                data.message,
                                'error'
                            );
                        }

                    },
                    error: function(data){
                        console.log(data);
                    }
                });

            });



        SCRIPT;
    }

    public function html()
    {
        Admin::script($this->script());

        $cat = '';
        foreach(Bam_ItemCategory('all',0,Admin::user()->school_id) as $category){
            $cat .= '<option value="'. $category->id.'">'.$category->category_name.'</option>';
        }

        $item = '';
        foreach(Bam_Items('all',0,Admin::user()->school_id) as $items){
            $item .= '<option value="'. $items->id.'">'.$items->item.'</option>';
        }

        $store = '';
        foreach(Bam_ItemStores('all',0,Admin::user()->school_id) as $st){
            $store .= '<option value="'. $st->id.'">'.$st->store_name.'</option>';
        }

        $sup = '';
        foreach(Bam_ItemSupplier('all',0,Admin::user()->school_id) as $supplier){
            $sup .= '<option value="'. $supplier->id.'">'.$supplier->name.'</option>';
        }

        return '
            <button type="button" class="btn btn-primary fa fa-plus " data-toggle="modal" data-target="#itemStock">
                New
            </button>
            <div class="modal fade" id="itemStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Item Stock</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data" action="'.url('admin/register-stock').'">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <input type="hidden" id="current_url" value="'.config('app.url').'">
                                <div class="form-group">
                                    <label for="formSelect">Category</label>
                                    <select name="item_category" id="item_category" class="form-control" required>
                                        <option>Select Category</option>
                                        '.$cat.'
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Category</small>
                                </div>
                                <div class="form-group">
                                    <label for="formSelect">Item</label>
                                    <select name="item" id="item" class="form-control" required>
                                     
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Item</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="formSelect">Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control">
                                        '.$sup.'
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Supplier</small>
                                </div>
                                <div class="form-group">
                                    <label for="form">Store</label>
                                    <select name="store_id" id="store_id" class="form-control" required>
                                        '.$store.'
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Store</small>
                                </div>
                                
                                
                                <div class="form-group">
                                    <label for="formSelect">Quantity</label>
                                    <input type="number" name="quantity" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="formSelect">Purchase Price </label>
                                    <input type="number" name="purchase_price" class="form-control" required>
                                </div>

                                

                                 <div class="form-group">
                                    <label for="formSelect">Attached Document *</label>
                                    <input type="file" name="document" class="form-control">
                                </div>
                                 <div class="form-group">
                                    <label for="formSelect">Add Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <input type="hidden"  name="added_by" value="'.Admin::user()->id.'">
                                <input type="hidden"  name="school_id" value="'.Admin::user()->school_id.'">
                                <button type="submit"  class="btn btn-primary" >Submit</button>
                                
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            
        ';
    }
}