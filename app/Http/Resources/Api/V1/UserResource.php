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
 * Class UserResource
 * @mixin User
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserResource extends JsonResource
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
            'gender' => $this->profile->gender,
            'birthday' => $this->profile->birthday,
            'country_code' => $this->profile->country_code,
            'province_id' => $this->profile->province_id,
            'city_id' => $this->profile->city_id,
            'district_id' => $this->profile->district_id,
            'address' => $this->profile->address,
            'website' => $this->profile->website,
            'introduction' => $this->profile->introduction,
            'bio' => $this->profile->bio,
        ];
    }
}
