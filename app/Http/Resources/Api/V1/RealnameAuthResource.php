<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\RealnameAuth;
use Illuminate\Http\Request;

/**
 * 实名认证状态
 * @mixin RealnameAuth
 * @see RealnameAuth
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RealnameAuthResource extends JsonResource
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
            'type' => $this->type,
            'real_name' => $this->real_name,
            'identity' => $this->identity,

            'id_card_front' => $this->when($this->isPersonal(), $this->id_card_front),
            'id_card_back' => $this->when($this->isPersonal(), $this->id_card_back),
            'id_card_in_hand' => $this->when($this->isPersonal(), $this->id_card_in_hand),

            'license' => $this->when($this->isEnterprise(), $this->license),
            'contact_person' => $this->when($this->isEnterprise(), $this->contact_person),
            'contact_mobile' => $this->when($this->isEnterprise(), $this->contact_mobile),
            'contact_email' => $this->when($this->isEnterprise(), $this->contact_email),

            'status' => $this->status,
            'failed_reason' => $this->failed_reason,
        ];
    }
}
