<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

/**
 * 登录历史
 *
 * @property int $id 记录ID
 * @property int $user_id 用户ID
 * @property string $ip 登录IP
 * @property int|null $port 端口
 * @property string $browser 登录使用的浏览器
 * @property string $user_agent 用户代理
 * @property string $address 用户地址
 * @property Carbon|null $login_at 登录时间
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class LoginHistory extends Model
{
    use HasFactory;
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    public const CREATED_AT = 'login_at';

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'login_histories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', 'ip', 'port', 'browser', 'user_agent', 'address', 'login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'ip' => 'string',
        'login_at' => 'datetime',
    ];

    /**
     * Perform any actions required before the model boots.
     */
    protected static function booted(): void
    {
        static::creating(function (LoginHistory $model) {
            $model->port = $model->port > 0 ? (int) $model->port : null;
        });
        static::created(function (LoginHistory $model) {
            $model->userExtra->increment('login_count', 1, [
                'last_login_ip' => $model->ip,
                'last_login_at' => $model->login_at,
            ]);
            if (static::isTodayLogged($model->user_id)) {//当天首次登录
                Event::dispatch(new \App\Events\User\TodayFirstLogged($model));
            }
        });
    }

    /**
     * 当天是否登录过
     */
    public static function isTodayLogged(int|string $userId): bool
    {
        return static::query()->where('user_id', $userId)->whereDate('login_at', '=', Carbon::now())->exists();
    }
}
