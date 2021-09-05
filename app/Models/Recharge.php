<?php

declare(strict_types=1);
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Larva\Transaction\Models\Charge;

/**
 * 钱包充值明细
 *
 * @property int $id ID
 * @property int $user_id 用户ID
 * @property int $amount 充值金额，单位分
 * @property string $trade_channel 支付渠道
 * @property string $trade_type 支付类型
 * @property string $status 状态
 * @property string $client_ip 客户端IP
 * @property Carbon|null $succeed_at 成功时间
 * @property Carbon $created_at 创建时间
 * @property Carbon|null $updated_at 更新时间
 *
 * @property Charge $charge 收单模型
 * @property Transaction $transaction 交易模型
 * @method static Recharge create(array $validated)
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Recharge extends Model
{
    use Traits\BelongsToUser;
    use Traits\UsingTimestampAsPrimaryKey;
    use Traits\DateTimeFormatter;

    public const STATUS_PENDING = 'pending';//处理中： pending
    public const STATUS_SUCCESS = 'succeeded';//完成： succeeded
    public const STATUS_FAILED = 'failed';//失败： failed

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'recharges';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'trade_channel', 'trade_type', 'status', 'client_ip', 'succeed_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'succeed_at'
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (Recharge $model) {
            /** @var Recharge $model */
            $model->id = $model->generateKey();
        });
        static::created(function (Recharge $model) {
            $model->charge()->create([
                'trade_channel' => $model->trade_channel,//付款渠道
                'trade_type' => $model->trade_type,//付款类型
                'total_amount' => $model->amount,
                'subject' => trans('transaction.wallet_recharge'),
                'description' => trans('transaction.wallet_recharge'),
                'client_ip' => $model->client_ip,
                'payer' => ['openid' => 'o3GYH1rsmMPxNMJ5-jzTnDG7_on4'],
            ]);
        });
    }

    /**
     * Get the entity's transaction.
     *
     * @return MorphOne
     */
    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'source');
    }

    /**
     * Get the entity's charge.
     *
     * @return MorphOne
     */
    public function charge(): MorphOne
    {
        return $this->morphOne(Charge::class, 'order');
    }

    /**
     * 设置交易成功
     */
    public function markSucceed(): void
    {
        $this->update([
            'trade_channel' => $this->charge->trade_channel,
            'trade_type' => $this->charge->trade_type,
            'status' => static::STATUS_SUCCESS,
            'succeed_at' => $this->freshTimestamp()
        ]);
        $this->transaction()->create([
            'user_id' => $this->user_id,
            'trade_type' => Transaction::TYPE_RECHARGE,
            'description' => trans('transaction.wallet_recharge'),
            'amount' => $this->amount,
            'client_ip' => $this->client_ip,
        ]);
        Event::dispatch(new \App\Events\RechargeSucceeded($this));
    }

    /**
     * 设置交易失败
     */
    public function markFailed(): void
    {
        $this->update(['status' => static::STATUS_FAILED]);
        Event::dispatch(new \App\Events\RechargeFailure($this));
    }

    /**
     * 状态
     * @return string[]
     */
    public static function getStatusMaps(): array
    {
        return [
            static::STATUS_PENDING => '等待付款',
            static::STATUS_SUCCESS => '充值成功',
            static::STATUS_FAILED => '充值失败',
        ];
    }

    /**
     * 获取状态Dot
     * @return string[]
     */
    public static function getStatusDots(): array
    {
        return [
            static::STATUS_PENDING => 'info',
            static::STATUS_SUCCESS => 'success',
            static::STATUS_FAILED => 'warning',
        ];
    }
}
