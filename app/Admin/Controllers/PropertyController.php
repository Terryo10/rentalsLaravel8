<?php

namespace App\Admin\Controllers;

use App\Models\Property;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PropertyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Property';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Property());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('categories_id', __('Categories id'));
        $grid->column('type', __('Type'));
        $grid->column('taken', __('Taken'));
        $grid->column('city', __('City'));
        $grid->column('title', __('Title'));
        $grid->column('province', __('Province'));
        $grid->column('country', __('Country'));
        $grid->column('yard_size', __('Yard size'));
        $grid->column('bedroom_number', __('Bedroom number'));
        $grid->column('toilet_number', __('Toilet number'));
        $grid->column('bathroom_number', __('Bathroom number'));
        $grid->column('garage_number', __('Garage number'));
        $grid->column('price', __('Price'));
        $grid->column('day_or_month', __('Day or month'));
        $grid->column('imagePath', __('ImagePath'));
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
        $show = new Show(Property::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('categories_id', __('Categories id'));
        $show->field('type', __('Type'));
        $show->field('taken', __('Taken'));
        $show->field('city', __('City'));
        $show->field('title', __('Title'));
        $show->field('province', __('Province'));
        $show->field('country', __('Country'));
        $show->field('yard_size', __('Yard size'));
        $show->field('bedroom_number', __('Bedroom number'));
        $show->field('toilet_number', __('Toilet number'));
        $show->field('bathroom_number', __('Bathroom number'));
        $show->field('garage_number', __('Garage number'));
        $show->field('price', __('Price'));
        $show->field('day_or_month', __('Day or month'));
        $show->field('imagePath', __('ImagePath'));
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
        $form = new Form(new Property());

        $form->number('user_id', __('User id'));
        $form->number('categories_id', __('Categories id'));
        $form->number('type', __('Type'))->default(1);
        $form->switch('taken', __('Taken'));
        $form->text('city', __('City'));
        $form->text('title', __('Title'));
        $form->text('province', __('Province'));
        $form->text('country', __('Country'));
        $form->text('yard_size', __('Yard size'));
        $form->text('bedroom_number', __('Bedroom number'));
        $form->text('toilet_number', __('Toilet number'));
        $form->text('bathroom_number', __('Bathroom number'));
        $form->text('garage_number', __('Garage number'));
        $form->decimal('price', __('Price'));
        $form->text('day_or_month', __('Day or month'));
        $form->text('imagePath', __('ImagePath'));

        return $form;
    }
}
