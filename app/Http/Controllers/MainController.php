<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Larva\Transaction\Transaction;

/**
 * Class MainController
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MainController extends Controller
{
    /**
     * Displays homepage.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        print_r(request()->getClientIp());
        exit;
        return view('main.index');
    }

    /**
     * 发起退款
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function refund(Request $request): JsonResponse
    {
        $charge = Transaction::getCharge($request->get('charge_id'));
        $state = $charge->refund('test');
        return response()->json($state->toArray());
    }

    /**
     * 付款成功
     * @return JsonResponse
     * @throws \Yansongda\Pay\Exceptions\GatewayException
     * @throws \Yansongda\Pay\Exceptions\InvalidArgumentException
     * @throws \Yansongda\Pay\Exceptions\InvalidSignException
     */
    public function charge(Request $request): JsonResponse
    {
        $charge = Transaction::getCharge($request->get('charge_id'));
        //$pay = Transaction::wechat()->find($charge->id);
        $state = $charge->markSucceeded('test');
        return response()->json($charge->toArray());
    }

    /**
     * Displays manifest.json.
     * @param Request $request
     * @return JsonResponse
     */
    public function manifest(Request $request): JsonResponse
    {
        $manifest = [
            'name' => settings('system.title'),
            'short_name' => config('app.name'),
            'description' => settings('system.description'),
            'icons' => [
                [
                    'src' => asset('img/favicon_128x128.png'),
                    'size' => '48x48 96x96'
                ],
                [
                    'src' => asset('img/favicon_256x256.png'),
                    'size' => '192x192 256x256'
                ]
            ],
            'theme_color' => '#fefefe',
            'display' => 'standalone',
            'start_url' => '/',
            'background_color' => '#212529',
        ];
        return response()->json($manifest, 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Displays redirect.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redirect(Request $request)
    {
        return view('main.redirect', ['url' => $request->get('url')]);
    }
}
