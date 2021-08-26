<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Events\RefreshTokenCreated;
use Laravel\Passport\Passport;

/**
 * Passport 事件
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PassportSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * 授权令牌创建成功
     * @param AccessTokenCreated $event
     */
    public function handleAccessTokenCreated(AccessTokenCreated $event)
    {
        if ($event->userId) {
            User::find($event->userId)->updateLogin(Request::ip(), Request::userAgent());
        }
    }

    /**
     * 刷新令牌创建成功
     * @param RefreshTokenCreated $event
     */
    public function handleRefreshTokenCreated(RefreshTokenCreated $event)
    {
        $token = Passport::token()->where('id', $event->accessTokenId)->first();
        if ($token && $token->user_id) {
            //User::find($token->user_id)->updateLogin(Request::ip(), Request::userAgent());
        }
    }

    /**
     * 为事件订阅者注册监听器
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(AccessTokenCreated::class, [PassportSubscriber::class, 'handleAccessTokenCreated']);
        $events->listen(RefreshTokenCreated::class, [PassportSubscriber::class, 'handleRefreshTokenCreated']);
    }
}
