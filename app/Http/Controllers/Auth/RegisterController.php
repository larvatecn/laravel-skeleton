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
use App\Http\Requests\Auth\MobileRegisterRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 前台用户注册
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
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
     * Show the application registration form.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        if ($request->user()) {
            return redirect(url()->previous());
        } elseif (!settings('user.enable_registration')) {
            return redirect(url()->previous())->with('status', trans('user.registration_closed'));
        }
        $this->setReferrer();
        return view('auth.register');
    }

    /**
     * 显示手机号码注册窗口
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function showMobileRegistrationForm(Request $request)
    {
        if ($request->user()) {
            return redirect(url()->previous());
        } elseif (!settings('user.enable_registration')) {
            return redirect(url()->previous())->with('status', trans('user.registration_closed'));
        }
        $this->setReferrer();
        return view('auth.register-mobile');
    }

    /**
     * 手机注册
     * @param MobileRegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function mobileRegister(MobileRegisterRequest $request)
    {
        event(new Registered($user = User::createByMobile($request->mobile, $request->password)));
        $this->guard()->login($user);
        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data): \Illuminate\Contracts\Validation\Validator
    {
        $rules = [
            'username' => ['required', 'string', 'max:255', 'username', 'keep_word', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'terms' => ['accepted'],
        ];
        if (config('app.env') != 'testing' && settings('user.enable_register_ticket')) {
            $rules['ticket'] = ['required', 'ticket:register'];//开启防水墙
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::createByUsernameAndEmail($data['username'], $data['email'], $data['password']);
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed|void
     */
    protected function registered(Request $request, $user)
    {
        //关联邀请码
        $user->connectReferrer($request->session()->pull('invite_code'));
    }
}
