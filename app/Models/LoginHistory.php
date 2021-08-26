<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Notifications\LoginNotification;
use hisorange\BrowserDetect\Contracts\ResultInterface;
use hisorange\BrowserDetect\Facade as Browser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Larva\Ip2Region\Ip2Region;

/**
 * 登录历史
 * @property int $id 记录ID
 * @property int $user_id 用户ID
 * @property string $ip 登录IP
 * @property string $browser 登录使用的浏览器
 * @property string $user_agent 用户代理
 * @property string $address 用户地址
 * @property Carbon|null $created_at
 * @property User $user
 *
 * @property-read ResultInterface|false $device
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class LoginHistory extends Model
{
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    public const UPDATED_AT = null;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'login_histories';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'ip', 'browser', 'user_agent', 'address'
    ];

    /**
     * 隐藏输出的属性
     *
     * @var array
     */
    protected $hidden = [
        'user_id'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($model) {
            $ipInfo = Ip2Region::btreeSearch($model->ip);
            $model->address = $ipInfo['region'];
            if ($model->device instanceof ResultInterface) {
                $model->browser = $model->device->browserName();
            }
            $model->saveQuietly();
            $model->sendLoginNotification();
        });
    }

    /**
     * 获取设备属性
     * @return ResultInterface|false
     */
    public function getDeviceAttribute()
    {
        if ($this->user_agent) {
            return Browser::parse($this->user_agent);
        }
        return false;
    }

    /**
     * 发送登录通知
     */
    public function sendLoginNotification()
    {
        if (settings('user.enable_login_email')) {
            $this->user->notify(new LoginNotification($this));
        }
    }
}
