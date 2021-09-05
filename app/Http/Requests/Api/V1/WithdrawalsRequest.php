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
 * 提现请求
 * @author Tongle Xu <xutongle@gmail.com>
 */
class WithdrawalsRequest extends FormRequest
{
    /**
     * @var int 最小提现
     */
    protected $withdrawalsMin = 0;

    /**
     * @var int 当前余额
     */
    protected $balance = 0;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var array 结算账户信息
     */
    public $metaData = [];

    public $account;

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
     * 准备验证数据
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->withdrawalsMin = config('services.balance.withdrawals_min', 100);
        $this->balance = $this->user()->available_amount;

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
            'amount' => [
                'required', 'numeric', 'withdrawals_min', 'max:' . $this->balance,
            ],
            'recipient_id' => [
                'required', 'numeric', 'exists:user_settle_accounts,id',
            ],
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    public function passedValidation()
    {
        $settleAccount = $this->user()->cashAccounts()->where('id', '=', $this->recipient_id)->firstOrFail();
        $this->channel = $settleAccount->channel;
        $this->account = $settleAccount->account;
        $this->metaData = $settleAccount->recipient;
    }

    /**
     * 获取错误消息提示
     * @return array
     */
    public function messages(): array
    {
        return [
            'amount.required' => '提现金额必填！',
            'amount.min' => '最低提现金额' . $this->withdrawalsMin . '元！',
            'amount.max' => '账户余额不足，请先充值！',
            'recipient_id.required' => '提现账户必须选择！',
            'recipient_id.exists' => '提现账户不存在！'
        ];
    }
}
