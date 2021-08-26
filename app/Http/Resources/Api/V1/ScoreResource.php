<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\Score;

/**
 * 积分交易明细
 * @mixin Score
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ScoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'username' => $this->user->username,
            'user_avatar' => $this->user->avatar,
            'score' => $this->score,
            'current_score' => $this->current_score,
            'description' => $this->description,
            'type' => $this->type,
            'typeName' => $this->typeName,
            'client_ip' => $this->client_ip,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
