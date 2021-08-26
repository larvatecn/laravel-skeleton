<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\Category;
use Illuminate\Http\Request;

/**
 * 栏目列表
 * @mixin Category
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumb' => $this->thumb,
            'title' => $this->title,
            'keywords' => $this->keywords,
            'description' => $this->description,
        ];
    }
}
