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
 * 余额充值
 * @property-read string $channel
 * @property-read string $type
 * @property-read int $amount
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RechargeRequest extends FormRequest
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
     * 准备验证数据
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'client_ip' => $this->getClientIp(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'trade_channel' => ['required', 'string'],
            'trade_type' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:1'],
            'client_ip' => ['required', 'ip']
        ];
    }
}
