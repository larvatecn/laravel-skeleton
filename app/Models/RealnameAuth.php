<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Services\FileService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

/**
 * 实名认证
 * @property int $user_id 用户ID
 * @property string $type 用户类型：0 个人用户 1 企业用户
 * @property string $real_name 真实姓名/企业名称
 * @property string $identity 身份证号码/营业执照号码
 *
 * @property string|null $id_card_front 证件正面照片
 * @property string|null $id_card_back 证件背面照片
 * @property string|null $id_card_in_hand 手持证件照片
 * @property string|null $license 营业执照照片
 *
 * @property string|null $contact_person 联系人
 * @property string|null $contact_mobile 联系手机
 * @property string|null $contact_email 联系邮箱
 *
 * @property int $status 认证状态：0 未认证 1 等待认证 2 认证通过 3 认证失败
 * @property string|null $failed_reason 失败原因
 * @property Carbon|null $verified_at 认证通过时间
 * @property Carbon|null $submitted_at 提交时间
 * @property Carbon $updated_at 更新时间
 *
 * @property-read boolean $isApproved 已认证
 * @property-read boolean $isPending 待审核
 * @property-read boolean $isRejected 已拒绝
 *
 * @method static RealnameAuth|null find($id)
 * @method static int count()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RealnameAuth extends Model
{
    use Traits\BelongsToUser;
    use Traits\DateTimeFormatter;

    public const CREATED_AT = null;
    //认证状态
    public const STATUS_UNSUBMITTED = 0;//暂未提交，初始状态
    public const STATUS_PENDING = 1;//等待认证
    public const STATUS_REJECTED = 2;//认证被拒绝
    public const STATUS_APPROVED = 3;//已经认证

    //认证类型
    public const TYPE_PERSONAL = 'personal';//个人
    public const TYPE_ENTERPRISE = 'enterprise';//企业

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'realname_auth';

    /**
     * @var string 主键字段名
     */
    protected $primaryKey = 'user_id';

    /**
     * @var bool 关闭自增
     */
    public $incrementing = false;

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'type', 'identity', 'real_name', 'id_card_front', 'id_card_back', 'id_card_in_hand', 'license',
        'contact_person', 'contact_mobile', 'contact_email',
        'status', 'verified_at', 'submitted_at', 'failed_reason'
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => 0,
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'updated_at', 'verified_at', 'submitted_at',
    ];

    /**
     * 获取证件正面照片
     * @param string|null $value
     * @return string
     */
    public function getIdCardFrontAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $this->getUploadManager()->temporaryUrl($value, $this->freshTimestamp()->addMinutes(15));
        }
        return '';
    }

    /**
     * 获取正面背面照片
     * @param string|null $value
     * @return string
     */
    public function getIdCardBackAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $this->getUploadManager()->temporaryUrl($value, $this->freshTimestamp()->addMinutes(15));
        }
        return '';
    }

    /**
     * 获取手持证件照片
     * @param string|null $value
     * @return string
     */
    public function getIdCardInHeadAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $this->getUploadManager()->temporaryUrl($value, $this->freshTimestamp()->addMinutes(15));
        }
        return '';
    }

    /**
     * 获取营业执照照片
     * @param string|null $value
     * @return string
     */
    public function getLicenseAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $this->getUploadManager()->temporaryUrl($value, $this->freshTimestamp()->addMinutes(15));
        }
        return '';
    }

    /**
     * 是否已审核
     * @return bool
     */
    public function getIsApprovedAttribute(): bool
    {
        return $this->status == static::STATUS_APPROVED;
    }

    /**
     * 是否待审核
     * @return bool
     */
    public function getIsPendingAttribute(): bool
    {
        return $this->status == static::STATUS_PENDING;
    }

    /**
     * 是否已拒绝
     * @return bool
     */
    public function getIsRejectedAttribute(): bool
    {
        return $this->status == static::STATUS_REJECTED;
    }

    /**
     * 是否是个人
     * @return bool
     */
    public function isPersonal(): bool
    {
        return $this->type == static::TYPE_PERSONAL;
    }

    /**
     * 是否是企业认证
     * @return bool
     */
    public function isEnterprise(): bool
    {
        return $this->type == static::TYPE_ENTERPRISE;
    }

    /**
     * 设置实名认证信息
     * @param array $data
     */
    public function setAuthData(array $data)
    {
        $uploadManager = $this->getUploadManager();
        if (isset($data['id_card_front']) && $data['id_card_front'] instanceof UploadedFile) {
            $data['id_card_front'] = $uploadManager->uploadIdCard($this->user, $data['id_card_front'], '_front');
        }
        if (isset($data['id_card_back']) && $data['id_card_back'] instanceof UploadedFile) {
            $data['id_card_back'] = $uploadManager->uploadIdCard($this->user, $data['id_card_back'], '_back');
        }
        if (isset($data['id_card_in_hand']) && $data['id_card_in_hand'] instanceof UploadedFile) {
            $data['id_card_in_hand'] = $uploadManager->uploadIdCard($this->user, $data['id_card_in_hand'], '_in_hand');
        }
        if (isset($data['license']) && $data['license'] instanceof UploadedFile) {
            $data['license'] = $uploadManager->uploadIdCard($this->user, $data['license'], '_license');
        }
        $data['status'] = static::STATUS_PENDING;
        $data['failed_reason'] = null;
        $data['submitted_at'] = $this->freshTimestamp();
        $this->update($data);
        Event::dispatch(new \App\Events\IdentityPending($this));
    }

    /**
     * 标记已审核
     * @return bool
     */
    public function markApproved(): bool
    {
        $this->status = static::STATUS_APPROVED;
        $this->verified_at = $this->freshTimestamp();
        $this->failed_reason = null;
        $status = $this->saveQuietly();
        $this->user->saveQuietly(['identified' => true]);
        Event::dispatch(new \App\Events\IdentityApproved($this));
        return $status;
    }

    /**
     * 标记审核拒绝
     * @param string $failedReason
     * @return bool
     */
    public function markRejected(string $failedReason): bool
    {
        $this->status = static::STATUS_REJECTED;
        $this->failed_reason = $failedReason;
        $status = $this->saveQuietly();
        $this->user->saveQuietly(['identified' => false]);
        Event::dispatch(new \App\Events\IdentityRejected($this));
        return $status;
    }

    /**
     * 标记待审核
     * @return bool
     */
    public function markPending(): bool
    {
        $this->status = static::STATUS_PENDING;
        $this->failed_reason = null;
        $status = $this->saveQuietly();
        $this->user->saveQuietly(['identified' => false]);
        Event::dispatch(new \App\Events\IdentityPending($this));
        return $status;
    }

    /**
     * 证件类型
     * @return string[]
     */
    public static function getTypes(): array
    {
        return [
            static::TYPE_PERSONAL => '个人',
            static::TYPE_ENTERPRISE => '企业',
        ];
    }

    /**
     * 获取状态
     * @return string[]
     */
    public static function getStatusLabels(): array
    {
        return [
            static::STATUS_UNSUBMITTED => '未提交',
            static::STATUS_PENDING => '待审核',
            static::STATUS_APPROVED => '已审核',
            static::STATUS_REJECTED => '拒绝',
        ];
    }

    /**
     * 获取状态Dot
     * @return string[]
     */
    public static function getStatusDots(): array
    {
        return [
            static::STATUS_UNSUBMITTED => 'info',
            static::STATUS_PENDING => 'info',
            static::STATUS_APPROVED => 'success',
            static::STATUS_REJECTED => 'error',
        ];
    }

    /**
     * 获取文件上传实例
     * @return FileService
     */
    protected function getUploadManager(): FileService
    {
        $uploadManager = FileService::make();
        $uploadManager->dir(FileService::DIRECTORY_IDCARD);
        return $uploadManager;
    }
}
