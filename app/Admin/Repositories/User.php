<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Repositories;

use Dcat\Admin\Repositories\EloquentRepository;

/**
 * 用户仓库
 * @author Tongle Xu <xutongle@gmail.com>
 */
class User extends EloquentRepository
{
    protected $eloquentClass = \App\Models\User::class;

    /**
     * 支持软删除
     * @return bool
     */
    public function isSoftDeletes(): bool
    {
        return true;
    }
}
