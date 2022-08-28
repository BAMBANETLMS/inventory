<?php

namespace BambanetLms\Inventory\Http\Controllers;

use BambanetLms\Inventory\Models\Bam_ItemStockIssue;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use BambanetLms\Inventory\Actions\Stock\StockIssue;
use BambanetLms\Inventory\Models\BamAcademicStudents;
use BambanetLms\Inventory\Actions\RestoreDeleted;

class Bam_ItemStockIssueController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Stock Issue';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bam_ItemStockIssue());

        $grid->model()->latest();
        if(!Admin::user()->isRole('administrator')){
            $grid->model()->where('school_id',Admin::user()->school_id);
        }

        $grid->column('id', __('Id'));
        //$grid->column('user_type', __('User type'));
        $grid->column('user_id_issuer', __('Issuer'))->display(function($added_by){
                return Bam_AdminUsersNameFromId($added_by);
        });
        $grid->column('user_id', __('User'))->display(function($id){
            if($this->user_type == 'Student'){
                 $data = BamAcademicStudents::where('id',$id)->count();

                if($data < 1){
                    return 'Student Deleted';
                }else{
                    $data=BamAcademicStudents::where('id',$id)->first();
                    return $data->first_name.' '.$data->other_names;
                }
            }else{
                return Bam_AdminUsersNameFromId($id);
            }

        });
        $grid->column('issue_date', __('Issue date'));
        $grid->column('return_date', __('Return date'));
        //$grid->column('notes', __('Notes'));
        /**$grid->column('item_category_id', __('Item category id'))->display(function($id){
            return Bam_ItemCategory('one',$id)->category_name;
        });**/
        $grid->column('items.item','Item');
        $grid->column('quantity', __('Quantity'));
        if(Admin::user()->inRoles(['administrator', 'principal'])){
            $grid->column('school.school_name','School');
            $grid->column('admin.name','Added By');
        }
        
        $grid->column('status', __('Status'))->switch()->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new StockIssue());
        });
        $grid->filter(function($filter) {
            // Range filter, call the model's `onlyTrashed` method to query the soft deleted data.
            $filter->scope('trashed', 'Recycle Bin')->onlyTrashed();

        });
        $grid->batchActions(function ($batch) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            if (isset($_GET['_scope_']) and $_GET['_scope_'] == 'trashed') {
                $batch->add(new RestoreDeleted());
            }
        });
        $grid->disableCreation();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Bam_ItemStockIssue::findOrFail($id));

         /**$show->field('id', __('Id'));
        $show->field('user_type', __('User type'));
        $show->field('user_id', __('User id'));
        $show->field('user_id_issuer', __('User id issuer'));
        $show->field('issue_date', __('Issue date'));
        $show->field('return_date', __('Return date'));
        $show->field('notes', __('Notes'));
        $show->field('item_category_id', __('Item category id'));
        $show->field('item', __('Item'));
        $show->field('quantity', __('Quantity'));
        $show->field('school_id', __('School id'));
        $show->field('added_by', __('Added by'));
        $show->field('deleted_at', __('Deleted at'));**/
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bam_ItemStockIssue());

        /**$form->text('user_type', __('User type'));
        $form->text('user_id', __('User id'));
        $form->text('user_id_issuer', __('User id issuer'));
        $form->text('issue_date', __('Issue date'));
        $form->text('return_date', __('Return date'));
        $form->text('notes', __('Notes'));
        $form->text('item_category_id', __('Item category id'));
        $form->text('item', __('Item'));
        $form->text('quantity', __('Quantity'));
        $form->text('school_id', __('School id'));
        $form->text('added_by', __('Added by'));**/
        $form->switch('status', __('Status'))->default(1);


        return $form;
    }
}
