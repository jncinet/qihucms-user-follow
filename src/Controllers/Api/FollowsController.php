<?php

namespace Qihucms\UserFollow\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Qihucms\UserFollow\Requests\FollowIndexRequest;
use Qihucms\UserFollow\Resources\UserFansCollection;
use Qihucms\UserFollow\Resources\UserFollowCollection;
use Qihucms\UserFollow\UserFollowRepository;

class FollowsController extends Controller
{
    private $follow;

    public function __construct(UserFollowRepository $follow)
    {
        $this->follow = $follow;
        $this->middleware('auth:api')->except(['index']);
    }

    /**
     * 根据会员ID获取用户关注列表、粉丝列表
     *
     * @param FollowIndexRequest $request
     * @return UserFollowCollection | UserFansCollection
     */
    public function userFollows(FollowIndexRequest $request)
    {
        $type = $request->get('type');
        $user_id = $request->get('user_id');
        $user_id = intval($user_id);
        $status = $request->get('status');

        if ($type === 'follow') {
            $result = $this->follow->followPaginate($user_id, $status);
            return new UserFollowCollection($result);
        }

        $result = $this->follow->fansPaginate($user_id, $status);
        return new UserFansCollection($result);
    }

    /**
     * 添加关注
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow($id)
    {
        if (Auth::id() == $id) {
            return $this->jsonResponse(
                [__('user-follow::message.cannot_follow_yourself')],
                '',
                422
            );
        }

        $result = $this->follow->setFollow(Auth::id(), $id);
        return $this->jsonResponse($result);
    }

    /**
     * 查询是否关注
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkFollow($id)
    {
        $result = [
            'user_id' => Auth::id(),
            'to_user_id' => $id,
            'is_follow' => $this->follow->isFollow(Auth::id(), $id),
            'is_fans' => $id == Auth::id() ? false : $this->follow->isFollow($id, Auth::id()),
        ];
        return $this->jsonResponse($result);
    }

    /**
     * 批量关注
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function follows(Request $request)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            $ids = array_unique($ids);
            $result = [];
            foreach ($ids as $key => $id) {
                if (Auth::id() != $id && User::where('id', $id)->exists()) {
                    $result[$key] = $this->follow->setFollow(Auth::id(), intval($id));
                } else {
                    $result[$key] = false;
                }
            }
            return $this->jsonResponse($result);
        }
        return $this->jsonResponse([], '', 422);
    }

    /**
     * 取消关注
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unFollow($id)
    {
        $result = $this->follow->unsetFollow(Auth::id(), $id);

        if ($result) {
            return $this->jsonResponse(['user_id' => $id], '');
        }

        return $this->jsonResponse([__('user-follow::message.cancel_follow_fail')], '', 422);
    }
}