<?php

namespace Qihucms\UserFollow\Controllers\Admin;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Qihucms\UserFollow\Models\UserFollow;

class FollowsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '关注列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserFollow());

        $grid->model()->where('status', '>', 0)->latest();

        $grid->disableCreateButton();
        $grid->disableActions();

        $grid->filter(function ($filter) {
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->equal('user_id', __('user-follow::follow.user_id'));
            $filter->equal('to_user_id', __('user-follow::follow.to_user_id'));
            $filter->equal('to_user_id', __('user-follow::follow.status.label'))
                ->select(__('user-follow::follow.status.value'));
            $filter->between('created_at', __('user-follow::follow.created_at'))->datetime();
        });

        $grid->column('id', 'ID');
        $grid->column('user.username', __('user-follow::follow.user_id'));
        $grid->column('to_user.username', __('user-follow::follow.to_user_id'));
        $grid->column('status', __('user-follow::follow.status.label'))
            ->using(__('user-follow::follow.status.value'));
        $grid->column('updated_at', __('user-follow::follow.created_at'));

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
        $show = new Show(UserFollow::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user_id', __('user-follow::follow.user_id'));
        $show->field('to_user_id', __('user-follow::follow.to_user_id'));
        $show->field('status', __('user-follow::follow.status.label'))
            ->using(__('user-follow::follow.status.value'));
        $show->field('created_at', __('admin.created_at'));
        $show->field('updated_at', __('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserFollow());
        $form->select('status', __('user-follow::follow.status.label'))
            ->options(__('user-follow::follow.status.value'));
        return $form;
    }
}
