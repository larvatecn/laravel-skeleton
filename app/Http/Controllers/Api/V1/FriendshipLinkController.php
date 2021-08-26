<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\FriendshipLinkResource;
use App\Models\FriendshipLink;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * 友情链接列表
 * @author Tongle Xu <xutongle@gmail.com>
 */
class FriendshipLinkController extends Controller
{
    /**
     * 友情链接列表
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function __invoke(Request $request): AnonymousResourceCollection
    {
        $items = FriendshipLink::type($request->get('type', 'all'))->orderByDesc('id')->get();
        return FriendshipLinkResource::collection($items);
    }
}
