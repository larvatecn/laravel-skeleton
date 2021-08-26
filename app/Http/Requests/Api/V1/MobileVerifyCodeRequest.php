<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\FormRequest;

/**
 * 手机验证码
 * @property-read string $mobile
 * @property-read string $scene
 * @property-read string $ticket
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MobileVerifyCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'mobile' => ['required', 'max:11', 'mobile', 'mobile_verify',],
            'scene' => ['string'],
        ];
        if (config('app.env') != 'testing' && settings('user.enable_login_ticket')) {
            $rules['ticket'] = ['required', 'ticket:verify_code'];//防水墙
        }
        return $rules;
    }
}
