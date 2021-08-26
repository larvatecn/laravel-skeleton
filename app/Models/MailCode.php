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
 * 邮件验证码
 * @property string $email 邮箱
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
class MailCode extends Model
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
    protected $table = 'mail_codes';

    /**
     * @var array 允许批量赋值属性
     */
    protected $fillable = ['email', 'code', 'ip', 'state', 'usage_at', 'send_at'];

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
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * 标记为已使用
     * @param string $email
     * @param string $code
     * @return bool
     */
    public static function makeUsed(string $email, string $code): bool
    {
        $verifyCode = static::query()->where('email', $email)->where('code', $code)->first();
        if ($verifyCode) {
            $verifyCode->update(['state' => 1, 'usage_at' => Carbon::now()]);
        }
        return true;
    }

    /**
     * 获取实例
     * @param string $email
     * @param string $ip
     * @param string $code
     * @return MailCode
     */
    public static function build(string $email, string $ip, string $code): MailCode
    {
        return static::create(['email' => $email, 'ip' => $ip, 'code' => $code, 'send_at' => Carbon::now()]);
    }

    /**
     * 获取最近24小时IP发送的总次数
     * @param string $ip
     * @return int
     */
    public static function getIpTodayCount(string $ip): int
    {
        return static::query()
            ->where('ip', $ip)
            ->where('send_at', '>=', Carbon::now()->subDay())
            ->count();
    }

    /**
     * 获取邮箱今日发送总数
     * @param string $email
     * @return int
     */
    public static function getMailTodayCount(string $email): int
    {
        return static::query()
            ->where('email', $email)
            ->where('send_at', Carbon::today())
            ->count();
    }

    /**
     * 获取今日发送总数
     * @param string $email
     * @param string $ip
     * @return int
     */
    public static function getTodayCount(string $email, string $ip): int
    {
        return static::getIpTodayCount($ip) + static::getMailTodayCount($email);
    }
}
