<?php

namespace Encore\PHPInfo\Http\Controllers;

use Encore\PHPInfo\Models\Bam_ItemSupplier;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use Encore\PHPInfo\Admin\Actions\RestoreDeleted;

class Bam_ItemSupplierController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item Supplier';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bam_ItemSupplier());
        $grid->model()->latest();
        if(!Admin::user()->isRole('administrator')){
            $grid->model()->where('school_id',Admin::user()->school_id);
        }
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('phone', __('Phone'));
        $grid->column('email', __('Email'));
        $grid->column('address', __('Address'));
        //$grid->column('contact_name', __('Contact name'));
        //$grid->column('contact_number', __('Contact number'));
        //$grid->column('contact_email', __('Contact email'));
        //$grid->column('contact_description', __('Contact description'));
        //$grid->column('deleted_at', __('Deleted at'));
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
        $show = new Show(Bam_ItemSupplier::findOrFail($id));

        /**$show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('phone', __('Phone'));
        $show->field('email', __('Email'));
        $show->field('address', __('Address'));
        $show->field('contact_name', __('Contact name'));
        $show->field('contact_number', __('Contact number'));
        $show->field('contact_email', __('Contact email'));
        $show->field('contact_description', __('Contact description'));
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
        $form = new Form(new Bam_ItemSupplier());

        $form->text('name', __('Name'))->required();
        $form->text('phone', __('Phone'))->required();
        $form->email('email', __('Email'));
        $form->text('address', __('Address'));
        $form->text('contact_name', __('Contact name'));
        $form->text('contact_number', __('Contact number'));
        $form->email('contact_email', __('Contact email'));
        $form->ckeditor('contact_description', __('Contact description'));
        if(Admin::user()->isRole('administrator')){
            $form->select('school_id', __('School'))->options(Bam_SchoolPluckedNames())->default(1)->required();
        }else{
            $form->hidden('school_id', __('School'))->value(Admin::user()->school_id)->readonly();
        }
        $form->hidden('added_by', __('Added By'))->value(Admin::user()->id)->readonly();
        return $form;
    }
}
