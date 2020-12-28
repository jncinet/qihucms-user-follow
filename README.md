## 安装
```shell
$ composer require jncinet/qihucms-user-follow
```

## 使用
### 数据迁移
```shell
$ php artisan migrate
```
### 发布资源
```shell
$ php artisan vendor:publish --provider="Qihucms\UserFollow\FollowServiceProvider"
```
### 可用方法
```php
// 判断是否关注
app('user-follow')->isFollow(int $user_id, int $to_user_id);

// 创建关注关系
app('user-follow')->setFollow(int $user_id, int $to_user_id);

// 取消关注
app('user-follow')->unsetFollow(int $user_id, int $to_user_id);

// 互相关注
app('user-follow')->setEachOther(int $user_id, int $to_user_id);

// 取消相互关注
app('user-follow')->unsetEachOther(int $user_id, int $to_user_id);

// 会员关注分页列表
app('user-follow')->followPaginate(int $user_id, $status = null, $limit = 15);

// 会员粉丝分页
app('user-follow')->fansPaginate(int $user_id, $status = null, $limit = 15);
```

### 路由及参数说明

#### 关注列表、粉丝列表

```
route('api.follow.index')
请求：GET
地址：/user/follows?user_id={$user_id}&type={$type}&status={$status}&page={$page}&limit={$limit}
参数：
int          $user_id （必填）需要查询的用户ID号
follow|fans  $type    （必填）查询类型：follow关注列表、fans粉丝列表
1|2          $status  （选填）如果只查询互相关注设置为2，默认为1查询所有关注
int          $page    （选填）页码
int          $limit   （选填）每页显示的条数
返回值：
{
    data: [
        {
            id：
            status：1｜2         // 1：关注 2：互相关注
            user: {会员信息},
            created_at: "3天前"  //关注时间
        },
        ...
    ],
    meta: {},
    links: {}
}

```

#### 添加关注

```php
route('api.follow.follow')
请求：POST
地址：/user/follow/{id=关注的用户ID号}
返回值：
{
    status: 'SUCCESS',
    result: {
        user_id: 关注的用户ID号
        to_user_id：被关注的用户ID号
        is_follow：是否关注
        is_fans：是否粉丝
    }
}
```

#### 查询是否关注

```php
route('api.follow.check')
请求：GET
地址：/user/follow/{$id=查询的用户ID号}
返回值：
{
    status: 'SUCCESS',
    result: {
        user_id: 关注的用户ID号
        to_user_id：被关注的用户ID号
        is_follow：是否关注
        is_fans：是否粉丝
    }
}
```

#### 批量关注

```php
route('api.follow.follows')
请求：POST
地址：/user/follows
参数：
array $ids （必填）需要关注的用户ID号组成的数组值如：[1,2,3,4]
返回值：
{
    status: 'SUCCESS',
    data: {
        1: {
            user_id: 关注的用户ID号
            to_user_id：被关注的用户ID号
            is_follow：是否关注
            is_fans：是否粉丝
        }
        2: false
        ...
    }
}
```

#### 取消关注

```php
route('api.follow.unfollow')
请求：DELETE
地址：/user/unfollow/{id=取消关注的会员ID}
返回值：
{
    status: 'SUCCESS',
    result: {
        user_id: 关注的用户ID号
        to_user_id：被关注的用户ID号
        is_follow：是否关注
        is_fans：是否粉丝
    }
}
```

### 事件调用

```php
// 添加关注
Qihucms\UserFollow\Events\Followed
// 取消关注
Qihucms\UserFollow\Events\UnFollowed
```