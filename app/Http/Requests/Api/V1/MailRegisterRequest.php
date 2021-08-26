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
 * 邮箱注册
 * @property-read string $username
 * @property-read string $email
 * @property-read string $password
 * @property-read string $ticket
 * @property-read string|null $invite_code
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MailRegisterRequest extends FormRequest
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
            'username' => ['required', 'string', 'max:255', 'username', 'keep_word', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'invite_code' => ['nullable', 'string', 'min:6'],//邀请码
        ];
        if (config('app.env') != 'testing' && settings('user.enable_login_ticket')) {
            $rules['ticket'] = ['required', 'ticket:register'];//防水墙
        }
        return $rules;
    }
}
