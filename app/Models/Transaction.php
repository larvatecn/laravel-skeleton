<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Exceptions\TransactionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;

/**
 * 余额交易明细
 *
 * @property int $id ID
 * @property int $user_id 用户ID
 * @property int $amount 交易金额
 * @property int $available_amount 交易后可用金额
 * @property string $description 描述
 * @property string $trade_type 交易类型
 * @property string $client_ip 客户端IP
 * @property Carbon $created_at 交易时间
 *
 * @property Model $source 来源模型
 * @property-read string $typeName 类别名称
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Transaction extends Model
{
    use Traits\BelongsToUser;
    use Traits\UsingTimestampAsPrimaryKey;
    use Traits\DateTimeFormatter;
    use Traits\UseTableNameAsMorphClass;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'available_amount', 'description', 'source', 'trade_type', 'client_ip'
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
    public const TYPE_WITHDRAWALS = 'withdrawals';//提现申请
    public const TYPE_WITHDRAWALS_FAILED = 'withdrawal_failed';//提现失败
    public const TYPE_WITHDRAWALS_REVOKED = 'withdrawal_revoked';//提现撤销
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
        static::creating(function (Transaction $model) {
            $model->id = $model->generateKey();
            $model->getConnection()->beginTransaction();//开始事务
            try {
                /** @var User $user */
                $user = $model->user()->lockForUpdate()->first();
                $model->available_amount = $user->available_amount + $model->amount;
                $user->updateQuietly(['available_amount' => $model->available_amount]);
                $model->getConnection()->commit();
            } catch (\Exception $e) {//回滚事务
                $model->getConnection()->rollback();
                throw new TransactionException($e->getMessage(), 500, $e);
            }
        });
    }

    /**
     * 获取所有操作类型
     * @return array
     */
    public static function getTypeMaps(): array
    {
        return [
            static::TYPE_RECHARGE => trans('transaction.' . static::TYPE_RECHARGE),
            static::TYPE_RECHARGE_REFUND => trans('transaction.' . static::TYPE_RECHARGE_REFUND),
            static::TYPE_RECHARGE_REFUND_FAILED => trans('transaction.' . static::TYPE_RECHARGE_REFUND_FAILED),
            static::TYPE_WITHDRAWALS => trans('transaction.' . static::TYPE_WITHDRAWALS),
            static::TYPE_WITHDRAWALS_FAILED => trans('transaction.' . static::TYPE_WITHDRAWALS_FAILED),
            static::TYPE_WITHDRAWALS_REVOKED => trans('transaction.' . static::TYPE_WITHDRAWALS_REVOKED),
            static::TYPE_PAYMENT => trans('transaction.' . static::TYPE_PAYMENT),
            static::TYPE_PAYMENT_REFUND => trans('transaction.' . static::TYPE_PAYMENT_REFUND),
            static::TYPE_TRANSFER => trans('transaction.' . static::TYPE_TRANSFER),
            static::TYPE_RECEIPTS_EXTRA => trans('transaction.' . static::TYPE_RECEIPTS_EXTRA),
            static::TYPE_ROYALTY => trans('transaction.' . static::TYPE_ROYALTY),
            static::TYPE_REWARD => trans('transaction.' . static::TYPE_REWARD),
        ];
    }

    /**
     * 获取Type名称
     * @return string
     */
    public function getTypeNameAttribute(): string
    {
        return trans('transaction.' . $this->trade_type);
    }

    /**
     * Get the source entity that the Transaction belongs to.
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}
