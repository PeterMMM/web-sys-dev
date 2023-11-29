<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Cookie;

class CookieController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Cookie';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Cookie());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('image', __('Image'));

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
        $show = new Show(Cookie::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('image', __('Image'))->image('http://localhost:8000/storage/', 100, 100);


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */





    protected function form()
    {
        $form = new Form(new Cookie());

        $form->text('title', __('Title'));
        $form->text('description', __('Description'));
        $form->image('image', __('Image'))->default('/storage/images/default_img.webp')->name(function ($file) {
            $extension = $file->guessExtension();
            $randomName = uniqid('image_') . '.' . $extension;
            return $randomName;
        });

        return $form;
    }
}
