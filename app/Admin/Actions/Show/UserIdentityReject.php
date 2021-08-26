<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Actions\Show;

use App\Admin\Forms\UserIdentityRejectForm;
use Dcat\Admin\Show\AbstractTool;
use Dcat\Admin\Widgets\Modal;

/**
 * 拒绝通过
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UserIdentityReject extends AbstractTool
{
    /**
     * @var string
     */
    protected $title = '<i class="feather icon-slash"></i> '.'拒绝通过';

    /**
     * @return string|void
     */
    public function render()
    {
        $form = UserIdentityRejectForm::make()->payload(['id' => $this->getKey()]);
        $modal = Modal::make()
            ->title($this->title())
            ->body($form)->lg()
            ->button(
                <<<HTML
<div class="btn-group pull-right btn-mini" style="margin-right: 5px">
<button class="btn btn-sm btn-danger">{$this->title()}</button>
</div>
HTML
            );
        return $modal->render();
    }
}
