<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources;

/**
 * JSON 响应
 * @author Tongle Xu <xutongle@gmail.com>
 */
class JsonResource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * Resource constructor.
     *
     * @param mixed $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
        if ($resource->wasRecentlyCreated) {
            $resource->refresh();
        }
    }
}
