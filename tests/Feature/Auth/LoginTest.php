<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试登录
     */
    public function testLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    /**
     * 测试邮箱登录
     */
    public function testEmailLogin()
    {
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'account' => $user->email,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
    }

    /**
     * 测试手机登录
     */
    public function testMobileLogin()
    {
        $user = User::factory()->mobile()->create();
        $response = $this->post('/login', [
            'account' => $user->mobile,
            'password' => 'password'
        ]);
        $response->assertStatus(302);
    }
}
