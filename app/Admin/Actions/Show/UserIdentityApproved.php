<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Actions\Show;

use App\Models\RealnameAuth;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Show\AbstractTool;
use Illuminate\Http\Request;

class UserIdentityApproved extends AbstractTool
{
    /**
     * @return string
     */
    protected $title = '<i class="feather icon-check"></i> '.'审核通过';

    /**
     * @var string
     */
    protected $style = 'btn btn-sm btn-success';

    /**
     * Handle the action request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $key = $this->getKey();
        RealnameAuth::findOrFail($key)->markApproved();
        return $this->response()->success('已审核通过')->refresh();
    }

    public function confirm(): array
    {
        return ['确定审核通过吗？'];
    }

    /**
     * @return string
     */
    protected function html(): string
    {
        $this->defaultHtmlAttribute('href', 'javascript:void(0)');

        return <<<HTML
<div class="btn-group pull-right btn-mini" style="margin-right: 5px">
<a {$this->formatHtmlAttributes()}>{$this->title()}</a>
</div>
HTML;
    }
}
