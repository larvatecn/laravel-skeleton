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
        return view('main.index');
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
