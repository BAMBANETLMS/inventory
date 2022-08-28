<?php

namespace BambanetLms\Inventory\Http\Controllers;

use BambanetLms\Inventory\Models\Bam_ItemStock;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;
use BambanetLms\Inventory\Actions\Item\ItemStock;
use BambanetLms\Inventory\Actions\RestoreDeleted;

class Bam_ItemStockController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item Stock';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bam_ItemStock());

        $grid->model()->latest();
        if(!Admin::user()->isRole('administrator')){
            $grid->model()->where('school_id',Admin::user()->school_id);
        }
        $grid->column('id', __('Id'));
        $grid->column('category.category_name','Category');
        $grid->column('items.item','Item');
        $grid->column('supplier.name','Supplier');
        $grid->column('store.store_name','Store');
        $grid->column('quantity', __('Quantity'));
        $grid->column('purchase_price', __('Purchase Price'))->display(function(){
            return Bam_Currency($this->purchase_price,$this->school_id); 
        });
        
        if(Admin::user()->inRoles(['administrator', 'principal'])){
            $grid->column('school.school_name','School');
            $grid->column('admin.name','Added By');
        }
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new ItemStock());
        });
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
        $grid->disableCreateButton();
        

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
        $show = new Show(Bam_ItemStock::findOrFail($id));

        /**$show->field('id', __('Id'));
        $show->field('item_category', __('Item category'));
        $show->field('item', __('Item'));
        $show->field('supplier_id', __('Supplier id'));
        $show->field('store_id', __('Store id'));
        $show->field('quantity', __('Quantity'));
        $show->field('purchase_price', __('Purchase price'));
        $show->field('document', __('Document'));
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
        $form = new Form(new Bam_ItemStock());
    
        $form->select('item_category', __('Category'))->options(Bam_ItemCategory('plucked',0,Admin::user()->school_id))->required();

        
        $form->select('item', __('Item'))->options(Bam_Items('plucked',0,Admin::user()->school_id))->required();
        $form->select('supplier_id', __('Supplier'))->options(Bam_ItemSupplier('plucked',0,Admin::user()->school_id))->required();
        $form->select('store_id', __('Store'))->options(Bam_ItemStores('plucked',0,Admin::user()->school_id))->required();
        $form->number('quantity', __('Quantity'))->required();
        $form->text('purchase_price', __('Purchase price'))->required();
        $form->image('document', __('Document'));
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
