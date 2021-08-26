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
 * 修改密码
 *
 * @property string $old_password
 * @property string $password
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ModifyPasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string', 'min:4', 'hash:' . $this->user()->password,],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
