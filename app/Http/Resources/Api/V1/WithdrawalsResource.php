<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\Withdrawals;

/**
 * 提现响应
 * @mixin Withdrawals
 * @author Tongle Xu <xutongle@gmail.com>
 */
class WithdrawalsResource extends JsonResource
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
            'user_avatar' => $this->user->avatar,
            'username' => $this->user->username,
            'amount' => $this->amount,
            'status' => $this->status,
            'trade_channel' => $this->trade_channel,
            'recipient' => $this->recipient,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->toDateTimeString(),
            'canceled_at' => $this->canceled_at ? $this->canceled_at->toDateTimeString() : null,
            'succeeded_at' => $this->succeeded_at ? $this->succeeded_at->toDateTimeString() : null,
        ];
    }
}
