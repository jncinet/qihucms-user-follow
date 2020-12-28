<?php

namespace Qihucms\UserFollow;

use Qihucms\UserFollow\Models\UserFollow;

class UserFollowRepository
{
    // 判断是否关注
    public function isFollow(int $user_id, int $to_user_id)
    {
        return UserFollow::where('user_id', $user_id)
            ->where('to_user_id', $to_user_id)
            ->where('status', '>', 0)
            ->exists();
    }

    /**
     * 创建关注关系
     *
     * @param int $user_id
     * @param int $to_user_id
     * @return array
     */
    public function setFollow(int $user_id, int $to_user_id)
    {
        $isFollow = $this->isFollow($user_id, $to_user_id);
        $isFans = $user_id != $to_user_id ? $this->isFollow($to_user_id, $user_id) : false;

        if ($isFollow) {
            return [
                'user_id' => $user_id,
                'to_user_id' => $to_user_id,
                'is_follow' => $isFollow,
                'is_fans' => $isFans
            ];
        }

        // 创建或更新关注关系
        UserFollow::create(
            [
                'user_id' => $user_id,
                'to_user_id' => $to_user_id,
                'status' => $isFans ? 2 : 1
            ]
        );

        // 更新互相关注
        if ($isFans) {
            $this->setEachOther($to_user_id, $user_id);
        }

        return [
            'user_id' => $user_id,
            'to_user_id' => $to_user_id,
            'is_follow' => true,
            'is_fans' => $isFans
        ];
    }

    /**
     * 取消关注
     *
     * @param int $user_id
     * @param int $to_user_id
     * @return bool
     */
    public function unsetFollow(int $user_id, int $to_user_id)
    {
        $result = UserFollow::where('user_id', $user_id)
            ->where('to_user_id', $to_user_id)
            ->delete();

        if ($result) {
            $this->unsetEachOther($user_id, $to_user_id);
        }

        return boolval($result);
    }

    /**
     * 互相关注
     *
     * @param int $user_id
     * @param int $to_user_id
     * @return mixed
     */
    public function setEachOther(int $user_id, int $to_user_id)
    {
        return UserFollow::where('user_id', $user_id)
            ->where('to_user_id', $to_user_id)
            ->where('status', 1)
            ->update(['status' => 2]);
    }

    /**
     * 取消相互关注
     *
     * @param int $user_id
     * @param int $to_user_id
     * @return mixed
     */
    public function unsetEachOther(int $user_id, int $to_user_id)
    {
        return UserFollow::where('user_id', $user_id)
            ->where('to_user_id', $to_user_id)
            ->where('status', 2)
            ->update(['status' => 1]);
    }

    /**
     * 关注分页
     *
     * @param int $user_id
     * @param int|null $status
     * @param int $limit
     * @return mixed
     */
    public function followPaginate(int $user_id, $status = null, $limit = 15)
    {
        if (is_null($status)) {
            $status = 1;
        }
        return UserFollow::where('user_id', $user_id)
            ->where('status', '>=', $status)
            ->with('to_user')
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }

    /**
     * 粉丝分页
     *
     * @param int $user_id
     * @param int $status
     * @param int $limit
     * @return mixed
     */
    public function fansPaginate(int $user_id, $status = null, $limit = 15)
    {
        if (is_null($status)) {
            $status = 1;
        }
        return UserFollow::where('to_user_id', $user_id)
            ->where('status', '>=', $status)
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate($limit);
    }
}