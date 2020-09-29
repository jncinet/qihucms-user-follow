<?php

namespace Qihucms\UserFollow\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Qihucms\UserFollow\Requests\FollowIndexRequest;
use Qihucms\UserFollow\Resources\UserFansCollection;
use Qihucms\UserFollow\Resources\UserFollowCollection;
use Qihucms\UserFollow\UserFollowRepository;

class FollowsController extends ApiController
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
    public function index(FollowIndexRequest $request)
    {
        $type = $request->get('type');
        $user_id = $request->get('user_id');
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $to_user_id = $request->input('user_id');
        $result = $this->follow->setFollow(Auth::id(), $to_user_id);
        return $this->jsonResponse($result);
    }

    /**
     * 查询是否关注
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = [
            'user_id' => Auth::id(),
            'to_user_id' => $id,
            'is_follow' => $this->follow->isFollow(Auth::id(), $id),
            'is_fans' => $this->follow->isFollow($id, Auth::id()),
        ];
        return $this->jsonResponse($result);
    }

    /**
     * 批量关注
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $ids = $request->input('ids');
        if (is_array($ids)) {
            $ids = array_unique($ids);
            $result = [];
            foreach ($ids as $key => $id) {
                if (User::where('id', $id)->exists()) {
                    $result[$key] = $this->follow->setFollow(Auth::id(), $id);
                } else {
                    $result[$key] = false;
                }
            }
            return $this->jsonResponse($result);
        }
        return $this->jsonResponse([], '参数不正确', 422);
    }

    /**
     * 取消关注
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $result = $this->follow->unsetFollow(Auth::id(), $id);
        return $this->jsonResponse($result);
    }
}