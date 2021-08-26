<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

/**
 * 修改个人资料
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ModifyProfileRequest extends FormRequest
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
            'username' => [
                'required', 'string', 'max:255', 'username', Rule::unique('users')->ignore($this->user()->id),
            ],
            'birthday' => 'sometimes|date',
            'gender' => 'nullable|integer|min:0|max:2',
            'country_code' => 'nullable|string',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'introduction' => 'nullable|string',
            'bio' => 'nullable|string',
        ];
    }
}
