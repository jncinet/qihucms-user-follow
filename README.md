## 安装

```shell
$ composer require jncinet/qihucms-user-follow
```

## 使用

### 路由能参数说明

#### 关注列表、粉丝列表

```php
route('api.user-follows.index')
请求：GET
地址：/user-follows?user_id={$user_id}&type={$type}&status={$status}&page={$page}&limit={$limit}
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
route('api.user-follows.store')
请求：POST
地址：/user-follows
参数：
int $user_id （必填）关注的用户ID号
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
route('api.user-follows.show')
请求：GET
地址：/user-follows/{$id}
参数：
int $id （必填）需要查询的用户ID号
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
route('api.user-follows.update')
请求：PUT｜PATCH
地址：/user-follows/0
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
route('api.user-follows.destroy')
请求：DELETE
地址：/user-follows
参数：
//_method：'DELETE', // 如需要请伪造删除方法
int $user_id （必填）关注的用户ID号
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