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
 * 设备注册请求
 * @property string $token
 * @property string $imei
 * @property string $imsi
 * @property string $model
 * @property string $vendor
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RegisterDeviceRequest extends FormRequest
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
        return [
            'token' => [
                'required',
                'string',
            ],
            'os' => [
                'required',
                'string',
            ],
            'imei' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'imsi' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'model' => [
                'sometimes',
                'nullable',
                'string',
            ],
            'vendor' => [
                'sometimes',
                'nullable',
                'string',
            ],
        ];
    }
}
