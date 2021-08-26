<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Tag 接口
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TagController extends Controller
{
    /**
     * 获取 Tag 列表
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Tag::query()->orderByDesc('frequency');
        $q = $request->get('q');
        if (mb_strlen($q) >= 2) {
            $query->where('name', 'LIKE', '%' . $q . '%');
        }
        $prePage = intval($request->get('per_page', 16));
        $items = $query->paginate($prePage);
        return TagResource::collection($items);
    }

    /**
     * 取 Tag 详情
     * @param Tag $tag
     * @return TagResource
     */
    public function show(Tag $tag): TagResource
    {
        return new TagResource($tag);
    }
}
