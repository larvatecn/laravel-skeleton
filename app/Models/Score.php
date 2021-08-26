<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Exceptions\ScoreException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * 积分交易
 *
 * @property string $id 流水号
 * @property int $user_id 用户ID
 * @property int $score 交易积分数
 * @property int $current_score 交易后积分数
 * @property string $description 交易描述
 * @property string $type 交易类型
 * @property string $client_ip 客户端IP
 * @property-read string $typeName 交易类型
 * @property Carbon $created_at 交易发起时间
 *
 * @property Model $source
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Score extends Model
{
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'scores';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'score', 'current_score', 'description', 'source', 'type', 'client_ip'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at'
    ];

    public const UPDATED_AT = null;

    public const TYPE_RECHARGE = 'recharge';//充值
    public const TYPE_RECHARGE_REFUND = 'recharge_refund';//充值退款
    public const TYPE_RECHARGE_REFUND_FAILED = 'recharge_refund_failed';//充值退款失败
    public const TYPE_PAYMENT = 'payment';//支付/收款
    public const TYPE_PAYMENT_REFUND = 'payment_refund';//退款/收到退款
    public const TYPE_TRANSFER = 'transfer';//转账/收到转账
    public const TYPE_RECEIPTS_EXTRA = 'receipts_extra';//赠送
    public const TYPE_ROYALTY = 'royalty';//分润/收到分润
    public const TYPE_REWARD = 'reward';//奖励/收到奖励

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            /** @var Score $model */
            $model->getConnection()->beginTransaction();//开始事务
            try {
                /** @var User $user */
                $user = $model->user()->lockForUpdate()->first();
                $model->current_score = $user->score + $model->score;
                $user->updateQuietly(['score' => $model->current_score]);
                $model->getConnection()->commit();
            } catch (\Exception $e) {//回滚事务
                $model->getConnection()->rollback();
                throw new ScoreException($e->getMessage(), 500);
            }
        });
    }

    /**
     * 获取所有操作类型
     * @return array
     */
    public static function getAllType(): array
    {
        return [
            static::TYPE_RECHARGE => trans('score.' . static::TYPE_RECHARGE),
            static::TYPE_RECHARGE_REFUND => trans('score.' . static::TYPE_RECHARGE_REFUND),
            static::TYPE_RECHARGE_REFUND_FAILED => trans('score.' . static::TYPE_RECHARGE_REFUND_FAILED),
            static::TYPE_PAYMENT => trans('score.' . static::TYPE_PAYMENT),
            static::TYPE_PAYMENT_REFUND => trans('score.' . static::TYPE_PAYMENT_REFUND),
            static::TYPE_TRANSFER => trans('score.' . static::TYPE_TRANSFER),
            static::TYPE_RECEIPTS_EXTRA => trans('score.' . static::TYPE_RECEIPTS_EXTRA),
            static::TYPE_ROYALTY => trans('score.' . static::TYPE_ROYALTY),
            static::TYPE_REWARD => trans('score.' . static::TYPE_REWARD),
        ];
    }

    /**
     * 获取 TypeName
     * @return string
     */
    public function getTypeNameAttribute(): string
    {
        $all = static::getAllType();
        return $all[$this->type] ?? trans('score.' . $this->type);
    }

    /**
     * Get the source entity that the Transaction belongs to.
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
