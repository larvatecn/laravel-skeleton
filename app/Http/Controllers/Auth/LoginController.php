<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Larva\Socialite\Models\SocialUser;

/**
 * 用户登录控制器
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected string $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username(): string
    {
        return 'account';
    }

    /**
     * 登录后的页面转向
     * @return mixed
     */
    public function redirectTo()
    {
        return $this->getReferrer($this->redirectTo);
    }

    /**
     * 显示登录表单
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        $this->setReferrer();
        return view('auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param Request $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
        if (config('app.env') != 'testing' && settings('user.enable_login_ticket')) {
            $rules['ticket'] = ['required', 'ticket:login'];//开启防水墙
        }
        $request->validate($rules);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request): array
    {
        $username = $request->input($this->username());
        if (preg_match(config('system.mobile_rule'), $username)) {
            $credentials = ['mobile' => $username, 'password' => $request->input('password'), 'status' => User::STATUS_NORMAL];
        } else {
            $credentials = ['email' => $username, 'password' => $request->input('password'), 'status' => User::STATUS_NORMAL];
        }
        return $credentials;
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param User $user
     */
    protected function authenticated(Request $request, $user)
    {
        //绑定请求
        if ($request->session()->has('social_id')) {
            $socialUser = SocialUser::find($request->session()->pull('social_id'));
            $socialUser->connect($user);
        }
        $user->updateLogin($request->getClientIp(), $request->userAgent());
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function loggedOut(Request $request): \Illuminate\Http\RedirectResponse
    {
        //返回注销前的页面
        return redirect()->back();
    }
}
