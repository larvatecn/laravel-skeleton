<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\FriendshipLink;
use Illuminate\Http\Request;

/**
 * 友情链接
 * @mixin FriendshipLink
 * @author Tongle Xu <xutongle@gmail.com>
 */
class FriendshipLinkResource extends JsonResource
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
            'title' => $this->title,
            'url' => $this->url,
            'logo' => $this->logo,
            'description' => $this->description,
            'expired_at' => $this->expired_at ? $this->expired_at->toDateTimeString() : null,
        ];
    }
}
