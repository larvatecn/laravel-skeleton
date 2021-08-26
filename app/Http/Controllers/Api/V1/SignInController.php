<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SignIn;
use Illuminate\Http\Request;

/**
 * 签到
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SignInController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function info(Request $request): array
    {
        return SignIn::getSignInInfo($request->user());
    }

    /**
     * 签到
     * @param Request $request
     * @return array|false
     */
    public function sign(Request $request)
    {
        return SignIn::sign($request->user(), $request->getClientIp());
    }
}
