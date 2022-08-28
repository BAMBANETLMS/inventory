<?php

//namespace App\Admin\Actions\Stock;
namespace BambanetLms\Inventory\Actions\Stock;

use Encore\Admin\Actions\Action;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
use DB;


class StockIssue extends Action
{
    protected $selector = '.stock-issue';

    public function handle(Request $request)
    {
        // $request ...

        return $this->response()->success('Add stock...')->refresh();
    }

    protected function script()
    {

        return <<<SCRIPT
            $('#item_category_id').on('change', function () {
                $.ajax({
                    type: "GET",
                    url: $('#current_url').val()+"/admin/stockajaxrequest",
                    data: {f: 'stock',s:'getitems',id:$('#item_category_id').val()},
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

            $('#user_type').on('change', function () {
                $.ajax({
                    type: "GET",
                    url: $('#current_url').val()+"/admin/stockajaxrequest",
                    data: {f: 'stock',s:'getuserfromrole',id:$('#user_type').val()},
                    success: function(data){
                        console.log(data);
                        if (data['errors']) {
                            Swal.fire(
                                'Error',
                                'An error occured: Fill All Required Fields',
                                'error'
                            );
                        }else if(data['status'] == 200){
                            $('#user_id').html(data.message);
                            
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

        $role = config('admin.database.roles_model');
        $roleModels =$role::get();

        $user_type = '';
        foreach($roleModels as $roleModel){
            $user_type .= '<option value="'. $roleModel->id.'">'.$roleModel->name.'</option>';
        }

        $cat = '';
        foreach(Bam_ItemCategory('all',0,Admin::user()->school_id) as $category){
            $cat .= '<option value="'. $category->id.'">'.$category->category_name.'</option>';
        }

        $user_id_issuers = '';
        if(Admin::user()->isRole('administrator')){
            foreach(DB::table('admin_users')->get() as $users){
                $user_id_issuers .= '<option value="'. $users->id.'">'.$users->username.'</option>';
            }
            $show_issuer = 'block';
        }else{
           $user_id_issuers = '<option value="'.Admin::user()->id.'" selected>'.Admin::user()->name.'</option>';
           $show_issuer = 'none;';
        }

        return '
            <button type="button" class="btn btn-primary fa fa-plus " data-toggle="modal" data-target="#itemStockIssue">
                Issue
            </button>
            <div class="modal fade" id="itemStockIssue" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add Item Stock</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" enctype="multipart/form-data" action="'.url('admin/issue-stock').'">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <input type="hidden" id="current_url" value="'.config('app.url').'">
                                <div class="form-group">
                                    <label for="formSelect">Role</label>
                                    <select name="user_type" id="user_type" class="form-control" required>
                                        <option>Select Role</option>
                                        <option value="Student">Student</option>
                                        '.$user_type.'
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Role</small>
                                </div>
                                <div class="form-group">
                                    <label for="formSelect">Issue To *</label>
                                    <select name="user_id" id="user_id" class="form-control" required>
                                     
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Issue To *</small>
                                </div>
                                
                                <div class="form-group" style="display:'.$show_issuer.'">
                                    <label for="formSelect">Issued By</label>
                                    <select name="user_id_issuer" id="user_id_issuer" class="form-control">
                                        '.$user_id_issuers.'
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Select Issued By</small>
                                </div>
                                <div class="form-group">
                                    <label for="formSelect">Issue Date</label>
                                    <input type="date" name="issue_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="formSelect">Return Date</label>
                                    <input type="date" name="return_date" class="form-control">
                                </div>
                                
                                <hr>
                                <div class="form-group">
                                    <label for="formSelect">Category</label>
                                    <select name="item_category_id" id="item_category_id" class="form-control" required>
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
                                    <label for="formSelect">Quantity</label>
                                    <input type="number"  name="quantity" class="form-control">
                                </div>

                                 <div class="form-group">
                                    <label for="formSelect">Add Notes</label>
                                    <textarea name="notes" class="form-control"></textarea>
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