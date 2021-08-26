<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Services\FileService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

/**
 * 友情链接管理
 * @property int $id ID
 * @property string $type 链接类型
 * @property string $title 链接标题
 * @property string $url 链接Url
 * @property string $logo_path Logo
 * @property string $description 链接描述
 * @property string $remark 备注
 * @property Carbon $expired_at 过期时间
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property-read string $logo
 *
 * @method static Builder active()
 * @method static Builder logo()
 * @method static Builder type($type)
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class FriendshipLink extends Model
{
    use Traits\DateTimeFormatter;

    public const UPDATED_AT = null;

    public const CACHE_TAG = 'friendship_links:';

    //版本约定
    public const TYPE_SPONSOR = 'sponsor';//赞助商
    public const TYPE_PARTNER = 'partner';//合作伙伴
    public const TYPE_HOME = 'home';//首页链接
    public const TYPE_INNER = 'inner';//内页链接
    public const TYPE_ALL = 'all';//全站链接

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'friendship_links';

    /**
     * @var array 允许批量赋值属性
     */
    protected $fillable = [
        'type', 'title', 'url', 'logo_path', 'description', 'remark', 'expired_at'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'expired_at', 'created_at', 'updated_at',
    ];

    protected $appends = [
        'logo'
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::saved(function ($model) {
            FriendshipLink::forgetCache($model->id);
        });
        static::deleted(function ($model) {
            FriendshipLink::forgetCache($model->id);
        });
    }

    /**
     * 只查询正常状态的链接
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where(function ($query) {
            /** @var Builder $query */
            $query->whereNull('expired_at')
                ->orWhere('expired_at', '>', now());
        });
    }

    /**
     * 查询 Logo 链接
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLogo(Builder $query): Builder
    {
        return $query->whereNotNull('logo');
    }

    /**
     * 查询特定类别链接
     *
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', '=', $type);
    }

    /**
     * 获取Logo存储位置
     * @return string
     */
    public function getLogoAttribute(): ?string
    {
        if (!empty($this->attributes['logo_path'])) {
            return FileService::make()->url($this->attributes['logo_path']);
        }
        return null;
    }

    /**
     * 获取连接类型
     * @return string[]
     */
    public static function getTypeLabels(): array
    {
        return [
            FriendshipLink::TYPE_SPONSOR => '赞助商',
            FriendshipLink::TYPE_PARTNER => '合作伙伴',
            FriendshipLink::TYPE_HOME => '首页链接',
            FriendshipLink::TYPE_INNER => '内页链接',
            FriendshipLink::TYPE_ALL => '全站链接'
        ];
    }

    /**
     * 通过ID获取内容
     * @param int|string $id
     * @return FriendshipLink
     */
    public static function findById($id): FriendshipLink
    {
        return Cache::rememberForever(static::CACHE_TAG . $id, function () use ($id) {
            return FriendshipLink::query()->find($id);
        });
    }

    /**
     * 删除缓存
     * @param int|string $id
     * @return void
     */
    public static function forgetCache($id)
    {
        Cache::forget(static::CACHE_TAG . $id);
        Cache::forget(static::CACHE_TAG . 'home:ids');
        Cache::forget(static::CACHE_TAG . 'inner:ids');
        Cache::forget(static::CACHE_TAG . 'sponsor:ids');
        Cache::forget(static::CACHE_TAG . 'partner:ids');
    }

    /**
     * 获取赞助商链接
     * @param int|string $limit
     * @param int|string $cacheMinutes 缓存时间
     * @return mixed
     */
    public static function sponsor($limit = 10, $cacheMinutes = 60)
    {
        $ids = Cache::remember(static::CACHE_TAG . 'sponsor:ids', Carbon::now()->addMinutes($cacheMinutes), function () use ($limit) {
            return FriendshipLink::active()->type(static::TYPE_SPONSOR)->orderByDesc('id')->limit($limit)->pluck('id');
        });
        return $ids->map(function ($id) {
            return static::findById($id);
        });
    }

    /**
     * 获取合作伙伴链接
     * @param int|string $limit
     * @param int|string $cacheMinutes 缓存时间
     * @return mixed
     */
    public static function partner($limit = 10, $cacheMinutes = 60)
    {
        $ids = Cache::remember(static::CACHE_TAG . 'partner:ids', Carbon::now()->addMinutes($cacheMinutes), function () use ($limit) {
            return FriendshipLink::active()->type(static::TYPE_PARTNER)->orderByDesc('id')->limit($limit)->pluck('id');
        });
        return $ids->map(function ($id) {
            return static::findById($id);
        });
    }

    /**
     * 获取 首页 链接
     * @param int|string $cacheMinutes 缓存时间
     * @return mixed
     */
    public static function home($cacheMinutes = 60)
    {
        $ids = Cache::remember(static::CACHE_TAG . 'home:ids', Carbon::now()->addMinutes($cacheMinutes), function () {
            return FriendshipLink::active()->whereIn('type', [static::TYPE_HOME, static::TYPE_ALL])->orderByDesc('id')->pluck('id');
        });
        return $ids->map(function ($id) {
            return static::findById($id);
        });
    }

    /**
     * 获取 内页 链接
     * @param int|string $cacheMinutes 缓存时间
     * @return mixed
     */
    public static function inner($cacheMinutes = 60)
    {
        $ids = Cache::remember(static::CACHE_TAG . 'inner:ids', Carbon::now()->addMinutes($cacheMinutes), function () {
            return FriendshipLink::active()->whereIn('type', [static::TYPE_INNER, static::TYPE_ALL])->orderByDesc('id')->pluck('id');
        });
        return $ids->map(function ($id) {
            return static::findById($id);
        });
    }
}
