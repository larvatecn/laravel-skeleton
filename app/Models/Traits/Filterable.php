<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * 搜索过滤
 * @mixin Model
 * @property array $filterable 搜索字段白名单
 * @property string $ignoreFilterValue 搜索值黑名单
 * @method static filter(array $input = [])
 * @author Tongle Xu <xutongle@gmail.com>
 */
trait Filterable
{
    /**
     * 过滤
     * @param Builder $query
     * @param array|null $input
     */
    public function scopeFilter(Builder $query, ?array $input = null)
    {
        $input = $input && \is_array($input) ? $input : \request()->query();

        foreach ($input as $key => $value) {
            if ($value == ($this->ignoreFilterValue ?? 'all')) {
                continue;
            }

            $method = 'filter' . Str::studly($key);
            if (\method_exists($this, $method)) {
                \call_user_func([$this, $method], $query, $value, $key);
            } elseif ($this->isFilterable($key)) {
                if (\is_array($value)) {
                    $query->whereIn($key, $value);
                } else {
                    $query->where($key, $value);
                }
            }
        }
    }

    /**
     * 是否是允许搜索的字段
     * @param string $key
     * @return bool
     */
    public function isFilterable(string $key): bool
    {
        return \property_exists($this, 'filterable') && \in_array($key, $this->filterable);
    }

    /**
     * 排序
     * @param Builder $query
     * @param string $value
     * @example
     * <pre>
     *  order_by=id:desc
     *  order_by=age:desc,created_at:asc...
     * </pre>
     *
     */
    public function filterOrderBy(Builder $query, string $value)
    {
        $segments = \explode(',', $value);
        foreach ($segments as $segment) {
            [$key, $direction] = array_pad(\explode(':', $segment), 2, 'desc');
            $query->orderBy($key, $direction);
        }
    }
}
