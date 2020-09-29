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
            $filter->equal('user_id', '关注会员ID');
            $filter->equal('to_user_id', '被关注会员ID');
            $filter->between('created_at', '关注时间')->datetime();
        });

        $grid->column('id', 'ID');
        $grid->column('user.username', '关注会员账号');
        $grid->column('user.nickname', '关注会员昵称');
        $grid->column('to_user.username', '被关注会员账号');
        $grid->column('to_user.nickname', '被关注会员昵称');
        $grid->column('status', '状态')
            ->using(['无效', '关注', '互相关注'])
            ->dot(['info', 'success', 'danger'], 'success');
        $grid->column('updated_at', '关注时间');

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
        $form->select('status', '状态')->options(['无效', '关注', '互相关注']);
        return $form;
    }
}
