<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * 标签处理
 * @property Tag[] $tags
 * @property string $tag_values 标签名称列表
 * @property string $tag_ids 标签ID列表
 *
 * @mixin  Model
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait Taggable
{
    protected $_tagValues;

    /**
     * Boot the trait.
     *
     * Listen for the deleting event of a model, then remove the relation between it and tags
     */
    protected static function bootTaggable(): void
    {
        static::saved(function ($model) {
            $model->delAllTags();
            $model->addTags($model->_tagValues);
        });
        static::deleted(function ($model) {
            $model->delAllTags();
        });
    }

    /**
     * @param array|null $tags
     */
    public function addTags(?array $tags): void
    {
        if (empty($tags)) {
            return;
        }
        foreach ($tags as $value) {
            $value = trim($value);
            if (mb_strlen($value) < 2 || empty($value) || is_numeric($value)) {
                continue;
            }
            /* @var Tag $tag */
            $tag = Tag::firstOrCreate(['name' => $value], ['frequency' => 0]);
            $tag->increment('frequency');
            $this->tags()->save($tag);
        }
    }

    /**
     * 删除所有tag
     */
    public function delAllTags(): void
    {
        foreach ($this->tags as $tag) {
            Tag::where('id', $tag->id)->where('frequency', '>', 0)->decrement('frequency');
        }
        $this->tags()->detach();
    }

    /**
     * 获取所有的标签
     * @return MorphToMany
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * 获取逗号分隔的tag
     * @return string
     */
    public function getTagValuesAttribute(): string
    {
        return $this->tags()->pluck('name')->implode(',');
    }

    /**
     * 获取逗号分隔的tag ID
     * @return string
     */
    public function getTagIdsAttribute(): string
    {
        return $this->tags()->pluck('id')->implode(',');
    }

    /**
     * Sets tags.
     * @param array $values
     */
    public function setTagValuesAttribute($values)
    {
        $this->_tagValues = $this->filterTagValues($values);
    }

    /**
     * Filters tags.
     * @param string|string[] $values
     * @return string[]
     */
    public function filterTagValues($values): array
    {
        return array_unique(preg_split(
            '/\s*,\s*/u',
            preg_replace('/\s+/u', ' ', is_array($values) ? implode(',', $values) : $values),
            -1,
            PREG_SPLIT_NO_EMPTY
        ));
    }
}
