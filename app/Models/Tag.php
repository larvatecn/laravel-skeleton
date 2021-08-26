<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use App\Events\TagCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

/**
 * Tag
 * @property int $id ID
 * @property string $name Tag名称
 * @property int $frequency 热度
 * @property string $title SEO标题
 * @property string $keywords SEO关键词
 * @property string $description SEO描述
 * @property Carbon $created_at 创建时间
 * @property Carbon $updated_at 更新时间
 *
 * @property-read string $url
 *
 * @method static Builder name($name)
 * @method static Tag|null find($id)
 * @method static int count()
 */
class Tag extends Model
{
    use HasFactory;
    use Traits\DateTimeFormatter;
    use SoftDeletes;

    public const CACHE_TAG = 'tags:';
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'tags';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'name', 'frequency', 'title', 'keywords', 'description'
    ];

    /**
     * 应该被调整为日期的属性
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at',
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'frequency' => 0,
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($model) {
            Event::dispatch(new TagCreated($model));
        });
        static::forceDeleted(function ($model) {
            DB::table('taggables')->where('tag_id', $model->id)->delete();
        });
    }

    /**
     * 查找指定的Tag
     * @param Builder $query
     * @param string $name
     * @return Builder
     */
    public function scopeName(Builder $query, string $name): Builder
    {
        return $query->where('name', $name);
    }

    /**
     * 通过ID获取内容
     * @param string $name
     * @return mixed
     */
    public static function findByName(string $name)
    {
        $item = static::name($name)->first();
        if ($item) {
            return static::find($item->id);
        }
        return false;
    }

    /**
     * 获取Title
     * @return string
     */
    public function getTitleAttribute()
    {
        if (!empty($this->attributes['title'])) {
            return $this->attributes['title'];
        }
        return $this->name;
    }

    /**
     * 获取 访问Url
     * @return string
     */
    public function getKeywordsAttribute()
    {
        if (!empty($this->attributes['keywords'])) {
            return $this->attributes['keywords'];
        }
        return $this->name;
    }

    /**
     * 获取 访问Url
     * @return string
     */
    public function getDescriptionAttribute()
    {
        if (!empty($this->attributes['description'])) {
            return $this->attributes['description'];
        }
        return $this->name;
    }
}
