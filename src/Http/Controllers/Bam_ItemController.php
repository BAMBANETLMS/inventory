<?php

namespace BambanetLms\Inventory\Http\Controllers;

use BambanetLms\Inventory\Models\Bam_Item;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use BambanetLms\Inventory\Admin\Actions\RestoreDeleted;

class Bam_ItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bam_Item());

        $grid->model()->latest();
        if(!Admin::user()->isRole('administrator')){
            $grid->model()->where('school_id',Admin::user()->school_id);
        }
        $grid->column('id', __('Id'));
        $grid->column('item', __('Item'));
        $grid->column('category.category_name','Category');
        $grid->column('unit', __('Unit'));
        //$grid->column('description', __('Description'));
        if(Admin::user()->inRoles(['administrator', 'principal'])){
            $grid->column('school.school_name','School');
            $grid->column('admin.name','Added By');
        }
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->filter(function($filter) {
            $filter->scope('trashed', 'Recycle Bin')->onlyTrashed();
        });
        $grid->batchActions(function ($batch) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            if (isset($_GET['_scope_']) and $_GET['_scope_'] == 'trashed') {
                $batch->add(new RestoreDeleted());
            }
        });
        

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
        $show = new Show(Bam_Item::findOrFail($id));

        /**$show->field('id', __('Id'));
        $show->field('item', __('Item'));
        $show->field('category_id', __('Category id'));
        $show->field('unit', __('Unit'));
        $show->field('description', __('Description'));
        $show->field('school_id', __('School id'));
        $show->field('added_by', __('Added by'));**/
        $show->field('deleted_at', __('Deleted at'));
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
        $form = new Form(new Bam_Item());

        $form->text('item', __('Item'));
        $form->select('category_id', __('Category'))->options(Bam_ItemCategory('plucked',0,Admin::user()->school_id));
        $form->text('unit', __('Unit'));
        $form->ckeditor('description', __('Description'));
        if(Admin::user()->isRole('administrator')){
            $form->select('school_id', __('School'))->options(Bam_SchoolPluckedNames())->default(1)->required();
        }else{
            $form->hidden('school_id', __('School'))->value(Admin::user()->school_id)->readonly();
        }
        $form->hidden('added_by', __('Added By'))->value(Admin::user()->id)->readonly();

        return $form;
    }
}
