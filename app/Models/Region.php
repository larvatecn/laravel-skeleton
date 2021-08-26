<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Models;

use Dcat\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\EloquentSortable\Sortable;

/**
 * 地区模型
 * @property int $id
 * @property int $parent_id
 * @property string $name
 * @property int $level
 * @property string $initial
 * @property string $pinyin
 * @property string $city_code
 * @property string $ad_code
 * @property string $lng_lat
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Region extends Model implements Sortable
{
    use ModelTree;

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'region';

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $titleColumn = 'name';

    protected $sortable = [
        // 设置排序字段名称
        'order_column_name' => 'order',
        // 是否在创建时自动排序，此参数建议设置为true
        'sort_when_creating' => true,
    ];

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'level', 'name', 'initial', 'pinyin', 'city_code', 'ad_code', 'lng_lat', 'order',
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'order' => 0,
    ];

    /**
     * 获取地区
     * @param string|int $parent_id
     * @param string[] $columns
     * @return \Illuminate\Support\Collection
     */
    public static function getRegion($parent_id = 0, array $columns = ['id', 'name']): \Illuminate\Support\Collection
    {
        return static::query()->select($columns)->where('parent_id', $parent_id)->orderBy('ad_code')->get();
    }

    /**
     * 获取省份
     * @param string[] $columns
     * @return \Illuminate\Support\Collection
     */
    public static function getProvince(array $columns = ['id', 'name']): \Illuminate\Support\Collection
    {
        return static::query()->select($columns)->where('level', 1)->orderBy('ad_code')->get();
    }

    /**
     * 获取城市
     * @param string|int $provinceId
     * @param string[] $columns
     * @return \Illuminate\Support\Collection
     */
    public static function getCity($provinceId, array $columns = ['id', 'name']): \Illuminate\Support\Collection
    {
        return static::query()->select($columns)->where('level', 2)->where('parent_id', $provinceId)->orderBy('ad_code')->get();
    }

    /**
     * 获取区县
     * @param string|int $cityId
     * @param string[] $columns
     * @return \Illuminate\Support\Collection
     */
    public static function getDistrict($cityId, array $columns = ['id', 'name']): \Illuminate\Support\Collection
    {
        return static::query()->select($columns)->where('parent_id', $cityId)->where('level', 3)->orderBy('ad_code')->get();
    }

    /**
     * 获取乡镇
     * @param string|int $districtId
     * @param string[] $columns
     * @return \Illuminate\Support\Collection
     */
    public static function getStreet($districtId, array $columns = ['id', 'name']): \Illuminate\Support\Collection
    {
        return static::query()->select($columns)->where('parent_id', $districtId)->where('level', 4)->orderBy('ad_code')->get();
    }

    /**
     * 搜索地区
     * @param string $keywords
     * @param string|int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public static function searchRegion(string $keywords, $parent_id = 0): \Illuminate\Support\Collection
    {
        return static::query()
            ->select(['id', 'name'])
            ->where('parent_id', $parent_id)
            ->where('level', 4)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('pinyin', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('initial', 'LIKE', '%' . $keywords . '%');
            })
            ->orderBy('ad_code')->get();
    }

    /**
     * 搜索省
     * @param string $keywords
     * @return \Illuminate\Support\Collection
     */
    public static function searchProvince(string $keywords): \Illuminate\Support\Collection
    {
        return static::query()
            ->select(['id', 'name'])
            ->where('parent_id', 1)
            ->where('level', 4)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('pinyin', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('initial', 'LIKE', '%' . $keywords . '%');
            })
            ->orderBy('ad_code')->get();
    }

    /**
     * 搜索城市
     * @param string $keywords
     * @param string|int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public static function searchCity(string $keywords, $parent_id = 0): \Illuminate\Support\Collection
    {
        return static::query()
            ->select(['id', 'name'])
            ->where('level', 2)
            ->where('parent_id', $parent_id)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('pinyin', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('initial', 'LIKE', '%' . $keywords . '%');
            })
            ->orderBy('ad_code')->get();
    }

    /**
     * 搜索区县
     * @param string $keywords
     * @param string|int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public static function searchDistrict(string $keywords, $parent_id = 0): \Illuminate\Support\Collection
    {
        return static::query()
            ->select(['id', 'name'])
            ->where('level', 3)
            ->where('parent_id', $parent_id)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('pinyin', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('initial', 'LIKE', '%' . $keywords . '%');
            })
            ->orderBy('ad_code')->get();
    }

    /**
     * 搜索乡镇街道
     * @param string $keywords
     * @param string|int $parent_id
     * @return \Illuminate\Support\Collection
     */
    public static function searchStreet(string $keywords, $parent_id = 0): \Illuminate\Support\Collection
    {
        return static::query()
            ->select(['id', 'name'])
            ->where('level', 4)
            ->where('parent_id', $parent_id)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('pinyin', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('initial', 'LIKE', '%' . $keywords . '%');
            })
            ->orderBy('ad_code')->get();
    }

    /**
     * 获取省下拉数据
     * @return \Illuminate\Support\Collection
     */
    public static function getProvinceSelect(): \Illuminate\Support\Collection
    {
        return static::query()->select(['id', 'name'])->where('level', 1)->orderBy('ad_code')->pluck('name', 'id');
    }

    /**
     * 获取市下拉数据
     * @return \Illuminate\Support\Collection
     */
    public static function getCitySelect(): \Illuminate\Support\Collection
    {
        return static::query()->select(['id', 'name'])->where('level', 2)->orderBy('ad_code')->pluck('name', 'id');
    }

    /**
     * 获取县下拉数据
     * @return \Illuminate\Support\Collection
     */
    public static function getDistrictSelect(): \Illuminate\Support\Collection
    {
        return static::query()->select(['id', 'name'])->where('level', 3)->orderBy('ad_code')->pluck('name', 'id');
    }
}
