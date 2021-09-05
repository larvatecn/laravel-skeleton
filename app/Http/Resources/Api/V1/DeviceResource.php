<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Resources\Api\V1;

use App\Http\Resources\JsonResource;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * 设备资料响应
 * @mixin Device
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class DeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'token' => $this->token,
            'os' => $this->os,
            'imei' => $this->imei,
            'imsi' => $this->imsi,
            'model' => $this->model,
            'vendor' => $this->vendor,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }

    /**
     * 自定义响应信息
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(200);
    }
}
