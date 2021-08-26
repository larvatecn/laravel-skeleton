<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Validators;

use App\Services\MobileVerifyCodeService;

/**
 * 验证手机号是否可以获取验证码
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MobileVerifyValidator
{
    public function validate($attribute, $value, $parameters, $validator): bool
    {
        $verifyCode = MobileVerifyCodeService::make($value, request()->ip());
        if (config('app.env') == 'testing') {
            return true;
        }
        //一个IP地址每天最多发送 20
        if ($verifyCode->getIpSendCount() > settings('sms.ip_count', 20)) {
            return false;
        }
        //一个手机号码每天最多发送 10条
        if ($verifyCode->getMobileSendCount() > settings('sms.mobile_count', 10)) {
            return false;
        }
        return true;
    }
}
