<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

/**
 * 手机注册请求
 * @property-read int $mobile
 * @property-read string $verify_code
 * @property-read string $password
 * @property-read boolean $terms
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MobileRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'mobile', 'unique:users'],
            'verify_code' => ['required', 'min:4', 'max:6', 'mobile_verify_code:mobile',],
            'password' => 'required|string|min:6|max:20',
            'terms' => ['accepted'],
        ];
    }
}
