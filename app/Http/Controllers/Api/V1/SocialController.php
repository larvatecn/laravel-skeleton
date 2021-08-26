<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SocialResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Larva\Socialite\Facades\Socialite;
use Larva\Socialite\Models\SocialUser;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * 社交账户
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SocialController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * 获取已经绑定的社交账户
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function socialAccounts(Request $request)
    {
        return SocialResource::collection($request->user()->socials);
    }

    /**
     * 绑定社交账户
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function connect(Request $request, $provider)
    {
        //获取社交 用户
        /** @var \Larva\Socialite\Contracts\User $socialUser */
        $socialUser = Socialite::driver($provider)->stateless()->user();
        $social = User::getSocialUser($provider, $socialUser, false);
        if (!$social->user && $social->connect($request->user())) {
            return response('', 200);
        }
        throw new AccessDeniedHttpException('This account has been used, please replace it!');
    }

    /**
     * 解绑社交账户
     *
     * @param Request $request
     * @param string $provider
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function disconnect(Request $request, $provider)
    {
        /** @var SocialUser $social */
        $social = $request->user()->socials()->where('provider', $provider)->first();
        if ($social && $social->disconnect()) {
            return $this->withNoContent();
        }
        throw new NotFoundHttpException('Object not found.');
    }
}
