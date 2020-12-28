<?php

use Illuminate\Routing\Router;

// 接口
Route::group([
    'prefix' => config('qihu.user_follow_prefix', 'user'),
    'namespace' => 'Qihucms\UserFollow\Controllers\Api',
    'middleware' => ['api'],
    'as' => 'api.follow.'
], function (Router $router) {
    // 会员粉丝或关注列表
    $router->get('follows', 'FollowsController@userFollows')->name('index');
    // 验证关注关系
    $router->get('follow/{id}', 'FollowsController@checkFollow')->name('check');
    // 关注
    $router->post('follow/{id}', 'FollowsController@follow')->name('follow');
    // 批量关注
    $router->post('follows', 'FollowsController@follows')->name('follows');
    // 取关
    $router->delete('unfollow/{id}', 'FollowsController@unFollow')->name('unfollow');
});

// 后台
Route::group([
    // 后台使用laravel-admin的前缀加上扩展的URL前缀
    'prefix' => config('admin.route.prefix') . '/user',
    // 后台管理的命名空间
    'namespace' => 'Qihucms\UserFollow\Controllers\Admin',
    // 后台的中间件，限制管理权限才能访问
    'middleware' => config('admin.route.middleware'),
    'as' => 'admin.'
], function (Router $router) {
    $router->resource('follows', 'FollowsController');
});