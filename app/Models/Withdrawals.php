<?php

declare(strict_types=1);
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Events\WithdrawalsCanceled;
use App\Events\WithdrawalsFailed;
use App\Events\WithdrawalsSucceeded;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Larva\Transaction\Models\Transfer;

/**
 * 钱包提现明细
 * @property string $id
 * @property int $user_id 用户ID
 * @property int $amount 金额
 * @property string $status 状态
 * @property string $trade_channel 渠道
 * @property string $recipient 支付凭证
 * @property string $client_ip 客户端IP
 * @property array $attach 附加参数
 * @property Carbon $created_at 创建时间
 * @property Carbon|null $canceled_at 取消时间
 * @property Carbon|null $succeeded_at 成功时间
 *
 * @property Transaction $transaction
 * @property Transfer $transfer
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Withdrawals extends Model
{
    use Traits\BelongsToUser;
    use Traits\UsingTimestampAsPrimaryKey;
    use Traits\DateTimeFormatter;

    public const STATUS_PENDING = 'pending';//处理中： pending
    public const STATUS_SUCCEEDED = 'succeeded';//完成： succeeded
    public const STATUS_FAILED = 'failed';//失败： failed
    public const STATUS_CANCELED = 'canceled';//取消： canceled

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'withdrawals';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'status', 'trade_channel', 'recipient', 'client_ip', 'attach', 'canceled_at', 'succeeded_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'canceled_at', 'succeeded_at'
    ];

    /**
     * 属性类型转换
     *
     * @var array
     */
    protected $casts = [
        'attach' => 'array',
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
     * 获取提现附加参数
     * @return array
     */
    public function getExtraAttribute(): array
    {
        return [
            //微信
            'type' => $this->attach['type'] ?? '',
            'user_name' => $this->attach['name'] ?? '',
            //支付宝
            'recipient_name' => $this->attach['name'] ?? '',
            'recipient_account_type' => $this->attach['account_type'] ?? ''
        ];
    }

    /**
     * Get the entity's transaction.
     *
     * @return morphOne
     */
    public function transaction(): morphOne
    {
        return $this->morphOne(Transaction::class, 'source');
    }

    /**
     * Get the entity's transfer.
     *
     * @return morphOne
     */
    public function transfer(): morphOne
    {
        return $this->morphOne(Transfer::class, 'order');
    }

    /**
     * 设置提现成功
     */
    public function markSucceeded(): bool
    {
        $status = $this->update([
            'status' => static::STATUS_SUCCEEDED,
            'succeeded_at' => $this->freshTimestamp()
        ]);
        Event::dispatch(new WithdrawalsSucceeded($this));
        return $status;
    }

    /**
     * 取消提现
     * @return bool
     */
    public function markCanceled(): bool
    {
        $this->transaction()->create([
            'user_id' => $this->user_id,
            'trade_type' => Transaction::TYPE_WITHDRAWALS_REVOKED,
            'description' => trans('transaction.withdrawal_revoked'),
            'amount' => $this->amount
        ]);
        $this->update(['status' => static::STATUS_CANCELED, 'canceled_at' => $this->freshTimestamp()]);
        Event::dispatch(new WithdrawalsCanceled($this));
        return true;
    }

    /**
     * 提现失败平账
     * @return bool
     */
    public function markFailed(): bool
    {
        $this->transaction()->create([
            'user_id' => $this->user_id,
            'trade_type' => Transaction::TYPE_WITHDRAWALS_FAILED,
            'description' => trans('transaction.withdrawal_failed'),
            'amount' => $this->amount
        ]);
        $this->update(['status' => static::STATUS_FAILED, 'canceled_at' => $this->freshTimestamp()]);
        Event::dispatch(new WithdrawalsFailed($this));
        return true;
    }

    /**
     * 状态
     * @return string[]
     */
    public static function getStatusMaps(): array
    {
        return [
            static::STATUS_PENDING => '等待处理',
            static::STATUS_SUCCEEDED => '提现成功',
            static::STATUS_FAILED => '提现失败',
            static::STATUS_CANCELED => '提现撤销',
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
            static::STATUS_SUCCEEDED => 'success',
            static::STATUS_FAILED => 'warning',
            static::STATUS_CANCELED => 'info',
        ];
    }
}
