<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Listeners;

use App\Events\EmailReset;
use App\Events\MobileReset;
use App\Events\MobileVerified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

/**
 * 用户事件订阅
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserSubscriber implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * 注册成功事件
     * @param Registered $event
     */
    public function handleRegistered(Registered $event)
    {
        if (settings('user.enable_welcome_email') && !empty($event->user->email) && method_exists($event->user, 'notify')) {//发送欢迎邮件
            $event->user->notify(new \App\Notifications\WelcomeNotification($event->user));
        }
    }

    /**
     * 邮件重置事件
     * @param EmailReset $event
     */
    public function handleMailReset(EmailReset $event)
    {
    }

    /**
     * 手机重置事件
     * @param MobileReset $event
     */
    public function handleMobileReset(MobileReset $event)
    {
    }

    /**
     * 手机验证事件
     * @param MobileVerified $event
     */
    public function handleMobileVerified(MobileVerified $event)
    {
    }

    /**
     * 为事件订阅者注册监听器
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Registered::class, [UserSubscriber::class, 'handleRegistered']);
        $events->listen(EmailReset::class, [UserSubscriber::class, 'handleMailReset']);
        $events->listen(MobileReset::class, [UserSubscriber::class, 'handleMobileReset']);
        $events->listen(MobileVerified::class, [UserSubscriber::class, 'handleMobileVerified']);
    }
}
