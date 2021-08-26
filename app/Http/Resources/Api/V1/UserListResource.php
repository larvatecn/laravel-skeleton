<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class UserListResource
 * @mixin User
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserListResource extends JsonResource
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
            'mobile' => $this->mobile,
            'username' => $this->username,
            'email' => $this->email,
            'avatar' => $this->avatar,
        ];
    }
}
