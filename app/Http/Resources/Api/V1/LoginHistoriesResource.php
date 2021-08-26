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
 * 登录历史响应
 *
 * @mixin \App\Models\LoginHistory
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class LoginHistoriesResource extends JsonResource
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
            'ip' => $this->ip,
            'user_agent' => $this->user_agent,
            'address' => $this->address,
            'browser' => $this->browser,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
