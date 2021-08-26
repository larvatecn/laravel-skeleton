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
 * 修改手机号码请求
 * @property-read int $mobile
 * @property-read string $verifyCode
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ModifyMobileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return (bool)$this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'max:11', 'mobile', 'unique:users',],
            'verify_code' => ['required', 'min:4', 'max:6', 'mobile_verify_code:mobile',],
        ];
    }
}
