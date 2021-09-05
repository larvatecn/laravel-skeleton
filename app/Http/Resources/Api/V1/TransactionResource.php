<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\Transaction;

/**
 * 钱包交易明细
 * @mixin Transaction
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TransactionResource extends JsonResource
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
            'available_amount' => $this->available_amount,
            'description' => $this->description,
            'trade_type' => $this->trade_type,
            'typeName' => $this->typeName,
            'client_ip' => $this->client_ip,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
