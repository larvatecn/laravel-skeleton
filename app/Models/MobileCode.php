<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * 短信验证码
 * @property int $id
 * @property string $scene 验证场景
 * @property string $mobile 手机号
 * @property string $code 验证码
 * @property string $ip IP地址
 * @property int $state 使用状态
 * @property Carbon $created_at 创建时间
 * @property Carbon $usage_at 使用时间
 * @property Carbon $send_at 发送时间
 * @property User $user
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MobileCode extends Model
{
    use Traits\DateTimeFormatter;

    //使用状态
    public const USED_STATE = 1;
    public const CREATED_AT = 'send_at';
    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mobile_codes';

    /**
     * @var array 允许批量赋值属性
     */
    protected $fillable = ['scene', 'mobile', 'code', 'ip', 'state', 'usage_at'];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'send_at', 'usage_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mobile', 'mobile');
    }

    /**
     * 记录验证码
     * @param string|int $mobile
     * @param string $ip
     * @param string $code
     * @param string $scene 验证码场景
     * @return MobileCode
     */
    public static function build($mobile, string $ip, string $code, string $scene = 'default'): MobileCode
    {
        return static::create(['mobile' => $mobile, 'ip' => $ip, 'code' => $code, 'scene' => $scene]);
    }

    /**
     * 标记为已使用
     * @param string|int $mobile
     * @param string $code
     * @return bool
     */
    public static function makeUsed($mobile, string $code): bool
    {
        $verifyCode = static::query()->where('mobile', $mobile)->where('code', $code)->first();
        if ($verifyCode) {
            $verifyCode->update(['state' => static::USED_STATE, 'usage_at' => $verifyCode->freshTimestamp()]);
        }
        return true;
    }

    /**
     * 获取今日发送总数
     * @param string|int $mobile
     * @param string $ip
     * @return int
     */
    public static function getTodayCount($mobile, string $ip): int
    {
        return static::getIpTodayCount($ip) + static::getMobileTodayCount($mobile);
    }

    /**
     * 获取IP今日发送总数
     * @param string $ip
     * @return int
     */
    public static function getIpTodayCount(string $ip): int
    {
        return static::query()
            ->where('ip', $ip)
            ->whereDay('send_at', Carbon::today())
            ->count();
    }

    /**
     * 获取今日发送总数
     * @param string $mobile
     * @return int
     */
    public static function getMobileTodayCount(string $mobile): int
    {
        return static::query()
            ->where('mobile', $mobile)
            ->whereDay('send_at', Carbon::today())
            ->count();
    }
}
