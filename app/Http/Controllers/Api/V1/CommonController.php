<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MailVerifyCodeRequest;
use App\Http\Requests\Api\V1\MobileVerifyCodeRequest;
use App\Services\MailVerifyCodeService;
use App\Services\MobileVerifyCodeService;
use Illuminate\Support\Facades\App;
use Larva\Support\ISO3166;

/**
 * 公共接口
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CommonController extends Controller
{
    /**
     * 短信验证码
     * @param MobileVerifyCodeRequest $request
     * @return array
     */
    public function mobileVerifyCode(MobileVerifyCodeRequest $request): array
    {
        $verifyCode = MobileVerifyCodeService::make($request->mobile, $request->getClientIp(), $request->scene);
        return $verifyCode->send();
    }

    /**
     * 邮件验证码
     * @param MailVerifyCodeRequest $request
     * @return array
     */
    public function mailVerifyCode(MailVerifyCodeRequest $request): array
    {
        $verifyCode = MailVerifyCodeService::make($request->email, $request->getClientIp());
        return $verifyCode->send();
    }

    /**
     * 国家列表接口
     * @return array
     */
    public function country(): array
    {
        $items = ISO3166::$countries;
        $countries = [];
        foreach ($items as $code => $value) {
            $country = [
                'label' => ISO3166::country($code, App::getLocale()),
                'value' => $code
            ];
            $countries[] = $country;
        }
        return $countries;
    }
}
