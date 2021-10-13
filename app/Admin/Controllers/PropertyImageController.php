<?php

namespace App\Admin\Controllers;

use App\Models\PropertyImages;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PropertyImageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PropertyImages';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PropertyImages());

        $grid->column('id', __('Id'));
        $grid->column('property_id', __('Property id'));
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
        $show = new Show(PropertyImages::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('property_id', __('Property id'));
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
        $form = new Form(new PropertyImages());

        $form->number('property_id', __('Property id'));
        $form->text('imagePath', __('ImagePath'));

        return $form;
    }
}
