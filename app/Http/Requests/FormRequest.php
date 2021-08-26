<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Http\Requests;

/**
 * 表单请求
 * @method \App\Models\User|null user()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        // Using policy for Authorization
        return true;
    }

    /**
     * 获取客户端端口
     * @return int
     */
    public function getRemotePort(): int
    {
        return (int)$this->server('REMOTE_PORT');
    }
}
