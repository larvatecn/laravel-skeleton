<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RealnameAuthRequest;
use App\Http\Resources\Api\V1\RealnameAuthResource;
use Illuminate\Http\Request;

/**
 * 实名认证API
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RealnameAuthController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * 获取实名认证状态
     */
    public function status(Request $request): RealnameAuthResource
    {
        return new RealnameAuthResource($request->user()->realnameAuth);
    }

    /**
     * 提交实名认证
     */
    public function putIdentity(RealnameAuthRequest $request): RealnameAuthResource
    {
        $request->user()->realnameAuth->setAuthData($request->validated());
        return new RealnameAuthResource($request->user()->realnameAuth);
    }
}
