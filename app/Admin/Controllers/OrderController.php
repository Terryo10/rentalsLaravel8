<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Order';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('amount', __('Amount'));
        $grid->column('poll_url', __('Poll url'));
        $grid->column('payment_method', __('Payment method'));
        $grid->column('phone_number', __('Phone number'));
        $grid->column('status', __('Status'));
        $grid->column('used', __('Used'));
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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('amount', __('Amount'));
        $show->field('poll_url', __('Poll url'));
        $show->field('payment_method', __('Payment method'));
        $show->field('phone_number', __('Phone number'));
        $show->field('status', __('Status'));
        $show->field('used', __('Used'));
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
        $form = new Form(new Order());

        $form->number('user_id', __('User id'));
        $form->decimal('amount', __('Amount'));
        $form->text('poll_url', __('Poll url'));
        $form->text('payment_method', __('Payment method'));
        $form->text('phone_number', __('Phone number'));
        $form->text('status', __('Status'));
        $form->switch('used', __('Used'));

        return $form;
    }
}
