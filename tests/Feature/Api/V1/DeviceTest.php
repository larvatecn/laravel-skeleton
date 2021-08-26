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
use Laravel\Passport\Passport;
use Tests\TestCase;

/**
 * Class DeviceTest
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class DeviceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 移动设备登记
     */
    public function testRegister()
    {
        $user = User::factory()->create();

        //发送 post 请求
        $response = $this->json('POST', url('api/v1/device/register'));
        $response->assertStatus(422);

        //发送 post 请求
        Passport::actingAs($user);
        $response = $this->json('POST', url('api/v1/device/register'), [
            'token' => 'token123456',
            'os' => 'android',
            'imei' => '123456',
            'imsi' => '123456',
            'model' => 'meta10',
            'vendor' => 'huawei',
        ]);
        //断言他是成功的
        $response->assertStatus(201)->assertJson([
            'user_id' => $user->id,
            'token' => 'token123456',
            'os' => 'android',
            'imei' => '123456',
            'imsi' => '123456',
            'model' => 'meta10',
            'vendor' => 'huawei',
        ]);
    }
}
