<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use Larva\Transaction\Models\Charge;

/**
 * 付款单响应
 * @mixin Charge
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ChargeResource extends JsonResource
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
            'paid' => $this->paid,
            'channel' => $this->trade_channel,
            'type' => $this->trade_type,
            'total_amount' => $this->total_amount,
            'currency' => $this->currency,
            'subject' => $this->subject,
            'client_ip' => $this->client_ip,
            'transaction_no' => $this->transaction_no,
            'failure' => $this->failure,
            'description' => $this->description,
            'credential' => $this->credential,
            'expired_at' => $this->expired_at,
            'succeed_at' => $this->succeed_at,
        ];
    }
}
