<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

/**
 * 控制器基类
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 响应 204
     * @return Response
     */
    public function withNoContent(): Response
    {
        return response('', 204);
    }

    /**
     * 记录来源页面
     * @param string|null $url
     */
    protected function setReferrer(string $url = null)
    {
        if (!Session::has('actions-referrer')) {
            if (is_null($url)) {
                $url = Url::previous();
            }
            Session::put('actions-referrer', $url);
        }
    }

    /**
     * 获取来源页面
     * @param string|null $redirectTo
     * @return mixed
     */
    protected function getReferrer(string $redirectTo = null)
    {
        return Session::pull('actions-referrer', $redirectTo);
    }
}
