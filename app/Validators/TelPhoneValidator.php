<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

/**
 * 验证是否是固话
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TelPhoneValidator
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        if (!preg_match(config('system.tel_rule'), $value)) {
            return false;
        }
        return true;
    }
}
