<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * 通知
 * @author Tongle Xu <xutongle@gmail.com>
 */
class NotificationController extends Controller
{
    /**
     * NotificationController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * 通知列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $prePage = intval($request->get('per_page', 16));
        $notifications = $request->user()->notifications()->paginate($prePage);
        return NotificationResource::collection($notifications);
    }

    /**
     * 未读通知列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function unread(Request $request)
    {
        $prePage = intval($request->get('per_page', 16));
        $notifications = $request->user()->unreadNotifications()->paginate($prePage);
        return NotificationResource::collection($notifications);
    }

    /**
     * 标记所有未读通知为已读
     * @param Request $request
     * @return Response
     */
    public function markAllRead(Request $request): Response
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return response('', 200);
    }

    /**
     * 标记指定未读通知为已读
     * @param Request $request
     * @return Response
     */
    public function markAsRead(Request $request): Response
    {
        $request->user()->unreadNotifications()->where('id', $request->post('id'))->update(['read_at' => now()]);
        return response('', 200);
    }

    /**
     * 未读消息
     * @param Request $request
     * @return JsonResponse
     */
    public function unreadCount(Request $request): JsonResponse
    {
        $unreadNotificationCount = $request->user()->unreadNotifications()->count();
        return response()->json(['unreadNotificationCount' => $unreadNotificationCount]);
    }
}
