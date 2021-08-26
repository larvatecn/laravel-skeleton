<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use Illuminate\Http\Request;

/**
 * 扩展资料响应
 * @mixin \App\Models\UserExtra
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserExtraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return $request->user()->extra->toArray();
    }
}
