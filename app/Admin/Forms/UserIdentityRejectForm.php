<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Forms;

use App\Models\RealnameAuth;
use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Form;

/**
 * Class UserIdentityForm
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserIdentityRejectForm extends Form implements LazyRenderable
{
    use LazyWidget;

    public function form()
    {
        $this->textarea('failed_reason', '拒绝理由')->rows(3)->required()->rules('string')->placeholder('请输入拒绝理由！');
    }

    /**
     * Handle the form request.
     *
     * @param array $input
     *
     * @return \Dcat\Admin\Http\JsonResponse
     */
    public function handle(array $input)
    {
        // 获取外部传递参数
        $id = $this->payload['id'] ?? null;
        RealnameAuth::findOrFail($id)->markRejected($input['failed_reason']);
        return $this->response()->success('已拒绝！')->refresh();
    }
}
