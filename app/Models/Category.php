<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

declare(strict_types=1);
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Services\FileService;
use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Overtrue\Pinyin\Pinyin;
use Spatie\EloquentSortable\Sortable;

/**
 * 栏目
 * @property int $id ID
 * @property int $parent_id 父ID
 * @property string $name 栏目名称
 * @property string $type 栏目类型
 * @property string $thumb_path 缩略图
 * @property string $title 网页Title
 * @property string $keywords 关键词
 * @property string $description 描述
 * @property int $order 排序
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 * @property-read string $thumb 缩略图连接
 * @property-read string $url 栏目链接
 *
 * @method static Builder|Category type($type)
 * @method static Builder|Category root()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Category extends Model implements Sortable
{
    use ModelTree, HasFactory, SoftDeletes;
    use Traits\DateTimeFormatter;

    public const CACHE_TAG = 'categories:';

    public const TYPE_ARTICLE = 'article';
    public const TYPE_DOWNLOAD = 'download';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * 允许批量赋值的属性
     * @var array
     */
    protected $fillable = [
        'id', 'parent_id', 'type', 'name', 'order', 'thumb_path', 'title', 'keywords', 'description'
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'parent_id' => 0,
        'order' => 0,
    ];

    /**
     * 排序字段
     * @var array
     */
    protected $sortable = [
        // 设置排序字段名称
        'order_column_name' => 'order',
        // 是否在创建时自动排序，此参数建议设置为true
        'sort_when_creating' => true,
    ];

    /**
     * 标题字段
     * @var string
     */
    protected $titleColumn = 'name';

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booting()
    {
        static::creating(function ($model) {
            if (!$model->slug) {
                $model->slug = (new Pinyin())->permalink($model->name);
            }
        });
        static::forceDeleted(function ($model) {
            FileService::make()->destroy($model->thumb_path);
        });
    }

    /**
     * 获取Type Label
     * @return string[]
     */
    public static function getTypeMaps(): array
    {
        return [
            static::TYPE_ARTICLE => '文章',
            static::TYPE_DOWNLOAD => '下载',
        ];
    }

    /**
     * Get the children relation.
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     * Get the parent relation.
     *
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    /**
     * 查找指定类型栏目
     * @param Builder $query
     * @param string $type
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * 查找顶级栏目
     * @param Builder $query
     * @return Builder
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->where('parent_id', 0);
    }

    /**
     * 获取子栏目
     * @return array
     */
    public function getChildrenIds(): array
    {
        return $this->children()->pluck('id')->all();
    }

    /**
     * 获取 栏目Title
     * @return string
     */
    public function getTitleAttribute(): string
    {
        if (!empty($this->attributes['title'])) {
            return $this->attributes['title'];
        }
        return $this->attributes['name'];
    }

    /**
     * 获取 栏目keywords
     * @return string
     */
    public function getKeywordsAttribute(): string
    {
        if (!empty($this->attributes['keywords'])) {
            return $this->attributes['keywords'];
        }
        return $this->attributes['name'];
    }

    /**
     * 获取 栏目 description
     * @return string
     */
    public function getDescriptionAttribute(): string
    {
        if (!empty($this->attributes['description'])) {
            return $this->attributes['description'];
        }
        return $this->attributes['name'];
    }

    /**
     * 获取栏目缩略图
     * @return string
     */
    public function getThumbAttribute(): string
    {
        if (!empty($this->attributes['thumb_path'])) {
            return FileService::make()->url($this->attributes['thumb_path']);
        }
        return asset('img/img_invalid.png');
    }

    /**
     * 获取顶级栏目下拉数据
     * @param string $type
     * @return Collection
     */
    public static function getRootSelect(string $type): Collection
    {
        return static::type($type)->root()->select(['id', 'name'])->orderBy('order')->pluck('name', 'id');
    }

    /**
     * 获取顶级栏目
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRootNodes(string $type): \Illuminate\Database\Eloquent\Collection
    {
        return static::type($type)->root()->select(['id', 'name'])->orderBy('order')->get();
    }
}
