<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Larva\Settings\Facade\Settings;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 测试检查用户名是否可用
     */
    public function testExistUsername()
    {
        $user = User::factory()->create();
        //发送 post 请求
        $response = $this->json('POST', url('api/v1/user/exists'), [
            'username' => $user->username,
        ]);
        //断言他是成功的
        $response->assertStatus(200)->assertExactJson([
            'exist' => false,
        ]);
    }

    /**
     * @test
     * 测试检查邮箱是否可用
     */
    public function testExistEmail()
    {
        $user = User::factory()->create();

        //发送 post 请求
        $response = $this->json('POST', url('api/v1/user/exists'), [
            'email' => $user->email,
        ]);

        //断言他是成功的
        $response->assertStatus(200)->assertExactJson([
            'exist' => false,
        ]);
    }

    /**
     * 测试检查手机是否可用
     */
    public function testExistMobile()
    {
        $user = User::factory()->mobile()->create();

        //发送 post 请求
        $response = $this->json('POST', url('api/v1/user/exists'), [
            'mobile' => $user->mobile,
        ]);

        //断言他是成功的
        $response->assertStatus(200)->assertExactJson([
            'exist' => false,
        ]);
    }

    /**
     * 测试检查空
     */
    public function testExistFailure()
    {
        //发送 post 请求
        $response = $this->json('POST', url('api/v1/user/exists'));
        //断言他是成功的
        $response->assertStatus(400);
    }

    /**
     * 手机注册
     */
    public function testMobileRegister()
    {
        Settings::set('user.enable_registration', '1', 'bool');//启用注册
        //User的数据
        $data = [
            'mobile' => '13800138180',
            'verify_code' => '123456',
            'password' => 'secret1234',
        ];
        //发送 post 请求
        $response = $this->json('POST', url('api/v1/user/mobile-register'), $data);
        //断言他是成功的
        $response->assertStatus(201)->assertJson([
            'mobile' => $data['mobile'],
        ]);
    }

    /**
     * 邮箱注册
     */
    public function testEmailRegister()
    {
        Settings::set('user.enable_registration', '1', 'bool');//启用注册
        //User的数据
        $data = [
            'email' => 'test@gmail.com',
            'username' => 'Test',
            'password' => 'secret1234',
        ];
        //发送 post 请求
        $response = $this->json('POST', url('api/v1/user/email-register'), $data);
        //断言他是成功的
        $response->assertStatus(201)->assertJson([
            'username' => $data['username'],
            'email' => $data['email'],
        ]);
    }

    /**
     * 测试发送激活邮件
     */
    public function testSendVerificationMail()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/user/send-verification-mail'));
        $response->assertStatus(200);
    }

    /**
     * 通过手机短信重置密码
     */
    public function testResetPasswordByMobile()
    {
        $user = User::factory()->mobile()->create();

        $response = $this->json('POST', url('api/v1/user/mobile-reset-password'), [
            'mobile' => $user->mobile,
            'verify_code' => '123456',
            'password' => 'secret1234',
        ]);
        $response->assertStatus(200);
    }

    /**
     * 获取个人资料
     */
    public function testProfile()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->json('GET', url('api/v1/user/profile'));
        $response->assertStatus(200)->assertJson([
            'username' => $user->username,
            'email' => $user->email,
            'mobile' => $user->mobile,
        ]);
    }

    /**
     * 获取扩展资料
     */
    public function testExtra()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->json('GET', url('api/v1/user/extra'));
        $response->assertStatus(200)->assertJson([
            'login_num' => 0,
            'views' => 0,
            'articles' => 0,
        ]);
    }

    /**
     * 验证手机号码
     */
    public function testVerifyMobile()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/user/verify-mobile'), [
            'mobile' => $user->mobile,
            'verify_code' => '123456',
        ]);
        $response->assertStatus(200);
    }

    /**
     * 修改邮箱
     */
    public function testModifyEMail()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/user/email'), [
            'email' => 'abcd@123a.com',
            'verify_code' => '1234',
        ]);
        $response->assertStatus(200);
    }

    /**
     * 修改手机号
     */
    public function testModifyMobile()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/user/mobile'), [
            'mobile' => '15166668888',
            'verify_code' => '123456',
        ]);
        $response->assertStatus(200);
    }

    /**
     * 修改个人资料
     */
    public function testModifyProfile()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/user/profile'), [
            'username' => '昵称',
            'birthday' => '2019-01-01',
            'gender' => 0,
            'country_code' => 'CN',
            'province_id' => 370000,
            'city_id' => 370100,
            'district_id' => 370102,
            'address' => '这是地址',
            'website' => 'https://www.larva.com.cn',
            'introduction' => '这是描述',
            'bio' => '这是签名'
        ]);
        $response->assertStatus(200)->assertJson([
            'username' => '昵称',
            'birthday' => '2019-01-01',
            'gender' => 0,
            'country_code' => 'CN',
            'province_id' => 370000,
            'city_id' => 370100,
            'district_id' => 370102,
            'address' => '这是地址',
            'website' => 'https://www.larva.com.cn',
            'introduction' => '这是描述',
            'bio' => '这是签名'
        ]);
    }

    /**
     * 修改头像
     */
    public function testModifyAvatar()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        Storage::fake(config('user.avatar_disk'));
        $file = UploadedFile::fake()->image('avatar.jpg')->size(100);
        $response = $this->json('POST', url('api/v1/user/avatar'), [
            'avatar' => $file,
        ]);
        $response->assertStatus(200);
    }

    /**
     * 修改密码
     */
    public function testModifyPassword()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);

        $response = $this->json('POST', url('api/v1/user/password'), [
            'old_password' => 'password123',
            'password' => '12345678'
        ]);
        $response->assertStatus(422);

        $response = $this->json('POST', url('api/v1/user/password'), [
            'old_password' => 'password',
            'password' => '12345678'
        ]);
        $response->assertStatus(200);
    }

    /**
     * 搜索用户
     */
    public function testSearch()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->getJson(url('api/v1/user/search') . '?q=' . mb_substr($user->username, 0, 2));
        $response->assertStatus(200);
    }

    /**
     * 注销删除自己
     */
    public function testDestroy()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->deleteJson(url('api/v1/user'));
        $response->assertStatus(204);
    }

    /**
     * 登录历史
     */
    public function testLoginHistories()
    {
        $user = User::factory()->mobile()->create();
        Passport::actingAs($user);
        $response = $this->getJson(url('api/v1/user/login-histories'));
        $response->assertStatus(200);
    }
}
