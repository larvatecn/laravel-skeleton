<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CategoryListRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * 栏目类别
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CategoryController extends Controller
{
    /**
     * 获取类别列表
     * @param CategoryListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(CategoryListRequest $request): AnonymousResourceCollection
    {
        $items = Category::type($request->type)->get();
        return CategoryResource::collection($items);
    }

    /**
     * 获取栏目详情
     * @param Category $category
     * @return CategoryResource
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }
}
