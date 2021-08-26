<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Requests\Api\V1;

use App\Http\Requests\FormRequest;
use App\Models\RealnameAuth;
use Illuminate\Validation\Rule;
use Larva\Support\IDCard;

/**
 * 实名认证请求
 * @property string $type 用户类型：personal 个人用户 enterprise 企业用户
 * @property string $real_name 真实姓名/企业名称
 * @property string $identity 身份证号码/营业执照号码
 * @property string $id_card_front 证件正面照片
 * @property string $id_card_back 证件背面照片
 * @property string $id_card_in_hand 手持证件照片
 * @property string $license 营业执照照片
 *
 * @property string $contact_person 联系人
 * @property string $contact_mobile 联系手机
 * @property string $contact_email 联系邮箱
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RealnameAuthRequest extends FormRequest
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
            'type' => [
                'required',
                Rule::in(array_keys(RealnameAuth::getTypes())),
            ],
            'real_name' => [
                'required',
                'string',
                'max:255',
            ],
            'identity' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->type == RealnameAuth::TYPE_PERSONAL && !IDCard::validateCard($value)) {
                        $fail($attribute . ' is invalid.');
                    }
                },
            ],

            'id_card_front' => ['image'],
            'id_card_back' => ['required_with:id_card_front', 'image'],
            'id_card_in_hand' => ['image'],

            'license' => [
                'exclude_unless:type,' . RealnameAuth::TYPE_ENTERPRISE,
                'image'
            ],
            'contact_person' => [
                'exclude_unless:type,' . RealnameAuth::TYPE_ENTERPRISE,
                'required',
                'string'
            ],
            'contact_mobile' => [
                'exclude_unless:type,' . RealnameAuth::TYPE_ENTERPRISE,
                'required',
                'mobile'
            ],
            'contact_email' => [
                'exclude_unless:type,' . RealnameAuth::TYPE_ENTERPRISE,
                'required',
                'email'
            ],
        ];
    }
}
