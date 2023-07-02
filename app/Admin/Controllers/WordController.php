<?php

namespace App\Admin\Controllers;

use App\Models\Word;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Word';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Word());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('dictionary_id', __('Dictionary id'));
        $grid->column('title', __('Title'));
        $grid->column('explain', __('Explain'));
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
        $show = new Show(Word::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('dictionary_id', __('Dictionary id'));
        $show->field('title', __('Title'));
        $show->field('explain', __('Explain'));
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
        $form = new Form(new Word());

        $form->number('user_id', __('User id'));
        $form->number('dictionary_id', __('Dictionary id'));
        $form->text('title', __('Title'));
        $form->textarea('explain', __('Explain'));

        return $form;
    }
}
