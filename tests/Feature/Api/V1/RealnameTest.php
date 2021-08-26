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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Class IdentificationTest
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RealnameTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 获取实名认证
     */
    public function testRealnameAuth()
    {
        $user = User::factory()->create();
        //发送 post 请求
        Passport::actingAs($user);
        $response = $this->json('GET', url('api/v1/realname/auth'));
        //断言他是成功的
        $response->assertStatus(200);
    }

    /**
     * 提交实名认证请求
     */
    public function testRealnameAuthSubmit()
    {
        $user = User::factory()->create();
        //发送 post 请求
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/realname/auth'), [
            'type' => 'personal',
            'real_name' => '张三',
            'identity' => '110101199003076237',
        ]);
        //断言他是成功的
        $response->assertStatus(200);
    }
}
