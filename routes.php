<?php

use Illuminate\Routing\Router;

// 手机端
Route::group([
    'namespace' => 'Qihucms\UserFollow\Controllers\Wap',
    'middleware' => ['web']
], function (Router $router) {
//    $router->resource('user-follows', 'FollowsController');
});

// 接口
Route::group([
    'domain' => config('qihu.api_domain'),
    'namespace' => 'Qihucms\UserFollow\Controllers\Api',
    'middleware' => ['api'],
    'as' => 'api.'
], function (Router $router) {
    $router->apiResource('user-follows', 'FollowsController');
});

// 后台
Route::group([
    // 后台使用laravel-admin的前缀加上扩展的URL前缀
    'prefix' => config('admin.route.prefix'),
    // 后台管理的命名空间
    'namespace' => 'Qihucms\UserFollow\Controllers\Admin',
    // 后台的中间件，限制管理权限才能访问
    'middleware' => config('admin.route.middleware'),
    'as' => 'admin.'
], function (Router $router) {
    $router->resource('user-follows', 'FollowsController');
    // 配置
//    $router->get('config', 'ConfigController@index')
//        ->name('article.config');
});