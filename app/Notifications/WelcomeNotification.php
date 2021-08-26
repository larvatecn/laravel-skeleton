<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

/**
 * 用户注册欢迎通知
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable|User
     */
    public $user;

    /**
     * Create a new notification instance.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable|User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(Lang::get('Welcome Registration :appName', ['appName' => config('app.name')]))
            ->line(Lang::get('Your registered account is :username', ['username' => $this->user->username]))
            ->line(Lang::get('Thank you for choosing, we will be happy to help you in the process of your subsequent use of the service.'));
    }
}
