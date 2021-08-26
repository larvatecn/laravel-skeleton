<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\Dictionary;

use App\Models\MailCode;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;

/**
 * 邮件验证码
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MailCodeController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return '邮件验证码';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new MailCode(), function (Grid $grid) {
            $grid->quickSearch(['id', 'email']);
            $grid->model()->orderBy('id', 'desc');
            $grid->column('id', 'ID')->sortable();
            $grid->column('email', '邮箱');
            $grid->column('code', '验证码');
            $grid->column('state', '使用状态')->bool();
            $grid->column('ip', 'IP地址');
            $grid->column('send_at', '发送时间')->sortable();
            $grid->column('usage_at', '验证时间')->sortable();

            $grid->disableRowSelector();
            $grid->disableViewButton();
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->paginate(10);
        });
    }
}
