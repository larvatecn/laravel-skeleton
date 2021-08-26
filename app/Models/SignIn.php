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
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

/**
 * 用户签到表
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $score 签到获取的积分
 * @property string $client_ip 签到用户的IP地址
 * @property Carbon $created_at 签到时间
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SignIn extends Model
{
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    public const UPDATED_AT = null;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'signings';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'score', 'transaction_id', 'client_ip'
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
        static::created(function (SignIn $model) {
            /** @var Score $score */
            $score = $model->score()->create([
                'user_id' => $model->user_id,
                'type' => Score::TYPE_REWARD,
                'description' => '签到',
                'score' => $model->score,
                'client_ip' => $model->client_ip,
            ]);
            $model->updateQuietly(['transaction_id' => $score->id]);
        });
    }

    /**
     * Get the entity's transaction.
     *
     * @return MorphOne
     */
    public function score(): MorphOne
    {
        return $this->morphOne(Score::class, 'source');
    }

    /**
     * 获取用户签到信息
     * @param User $user
     * @return array
     */
    public static function getSignInInfo(User $user): array
    {
        $signed = static::isSigned($user->id);
        $totalCount = static::query()->where('user_id', $user->id)->count();
        $serialCount = 0;
        if ($user->extra->first_sign_in_at) {
            $serialCount = static::query()->whereBetween('created_at', [$user->extra->first_sign_in_at->startOfDay(), Carbon::now()->endOfDay()])->count();
        }
        return compact('signed', 'totalCount', 'serialCount');
    }

    /**
     * 签到
     * @param User $user
     * @param string|null $clientIp
     * @return array|false
     */
    public static function sign(User $user, string $clientIp = null)
    {
        if (!static::isSigned($user->id)) {
            $score = (int)settings('score.signing', 10);
            if (!static::yesterdayIsSigned($user->id)) {//昨天是否签到
                $user->extra->updateQuietly(['first_sign_in_at' => $user->freshTimestamp()]);
            } else {
                $diff = $user->extra->first_sign_in_at->diff(Carbon::now()->endOfDay());
                if ($diff->days + 1 >= 7) {
                    $score = (int)settings('score.signing7day', 20);
                }
            }
            $signing = static::create(['user_id' => $user->id, 'score' => $score, 'client_ip' => $clientIp]);
            Event::dispatch(new \App\Events\Signed($signing));
            return static::getSignInInfo($user);
        } else {
            return false;
        }
    }

    /**
     * 今天是否已经签到
     * @param int $userId
     * @return bool
     */
    public static function isSigned(int $userId): bool
    {
        return static::query()->where('user_id', $userId)->whereDate('created_at', Carbon::today())->exists();
    }

    /**
     * 昨天是否签到
     * @param int $userId
     * @return bool
     */
    public static function yesterdayIsSigned(int $userId): bool
    {
        return static::query()->where('user_id', $userId)->whereDate('created_at', Carbon::yesterday())->exists();
    }
}
