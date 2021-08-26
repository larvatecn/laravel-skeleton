<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Larva\Settings\Facade\Settings;
use Tests\TestCase;

/**
 * Class RegisterTest
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试注册页面
     */
    public function testRegisterPage()
    {
        $response = $this->get(url('/register'));
        $response->assertStatus(302);

        Settings::set('user.enable_registration', '1', 'bool');//启用注册

        $response = $this->get(url('/register'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');

        $response = $this->get(url('/register/mobile'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.register-mobile');
    }

    /**
     * 邮箱注册
     */
    public function testEmailRegister()
    {
        $uri = url('/');
        $data = [
            'username' => 'test123',
            'email' => 'test@deema.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => 'true'
        ];
        $response = $this->withSession(['actions-redirect' => $uri])->post(url('register'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => 'test@deema.com',
        ]);
    }

    /**
     * 手机注册
     */
    public function testMobileRegister()
    {
        $uri = url('/');
        $response = $this->withSession(['actions-redirect' => $uri])->post(url('/register/mobile'), [
            'mobile' => '13012345678',
            'password' => 'password',
            'verify_code' => '123456',
            'terms' => 'true'
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'mobile' => '13012345678',
        ]);
    }
}
