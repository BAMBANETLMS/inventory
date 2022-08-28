<?php

namespace BambanetLms\Inventory\Http\Controllers;

use BambanetLms\Inventory\Models\Bam_ItemCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use BambanetLms\Inventory\Admin\Actions\RestoreDeleted;

class Bam_ItemCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item Category';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bam_ItemCategory());
        $grid->model()->latest();
        if(!Admin::user()->isRole('administrator')){
            $grid->model()->where('school_id',Admin::user()->school_id);
        }
        $grid->column('id', __('Id'));
        $grid->column('category_name', __('Category Name'));
        $grid->column('description', __('Description'));
        if(Admin::user()->inRoles(['administrator', 'principal'])){
            $grid->column('school.school_name','School');
            $grid->column('admin.name','Added By');
        }
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
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Bam_ItemCategory::findOrFail($id));

        /**$show->field('id', __('Id'));
        $show->field('category_name', __('Category name'));
        $show->field('description', __('Description'));
        $show->field('school_id', __('School id'));
        $show->field('added_by', __('Added by'));
        $show->field('deleted_at', __('Deleted at'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));**/

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Bam_ItemCategory());

        $form->text('category_name', __('Category name'));
        $form->textarea('description', __('Description'));
        if(Admin::user()->isRole('administrator')){
            $form->select('school_id', __('School'))->options(Bam_SchoolPluckedNames())->default(1)->required();
        }else{
            $form->hidden('school_id', __('School'))->value(Admin::user()->school_id)->readonly();
        }
        $form->hidden('added_by', __('Added By'))->value(Admin::user()->id)->readonly();

        return $form;
    }
}
