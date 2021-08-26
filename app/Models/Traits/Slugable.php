<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;
use Overtrue\Pinyin\Pinyin;

/**
 * Slug 能力
 * @mixin  Model
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait Slugable
{
    /**
     * Boot the trait.
     *
     * Listen for the deleting event of a model, then remove the relation between it and tags
     */
    protected static function bootSlugable(): void
    {
        static::creating(function ($model) {
            if (!$model->slug) {
                $pinyin = (new Pinyin())->permalink($model->subject);
                $model->slug = static::generateSlug($pinyin);
            }
        });
    }

    /**
     * 生成一个Slug
     * @param string $slug Slug
     * @return string
     */
    public static function generateSlug(string $slug): string
    {
        if (static::query()->where('slug', '=', $slug)->exists()) {
            $row = static::query()->max('id');
            $slug = $slug . ++$row;
        }
        return $slug;
    }
}
