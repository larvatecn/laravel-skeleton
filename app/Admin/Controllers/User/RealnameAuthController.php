<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\User;

use App\Models\RealnameAuth;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Show;

/**
 * 实名认证
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RealnameAuthController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return '实名认证';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new RealnameAuth(), function (Grid $grid) {
            $grid->filter(function (Grid\Filter $filter) {
                //右侧搜索
                $filter->equal('user_id');
                $filter->equal('real_name');
                $filter->equal('identity');
                //顶部筛选
                $filter->scope('approved', '已审核')->where('status', RealnameAuth::STATUS_APPROVED);
                $filter->scope('pending', '待审核')->where('status', RealnameAuth::STATUS_PENDING);
                $filter->scope('rejected', '已拒绝')->where('status', RealnameAuth::STATUS_REJECTED);
            });
            $grid->quickSearch(['user_id', 'real_name', 'identity']);
            $grid->model()->orderBy('user_id', 'desc');
            $grid->model()->with(['user']);
            $grid->column('user_id', '用户ID')->sortable();
            $grid->column('user.username', '用户名');
            $grid->column('real_name', '真实姓名');
            $grid->column('type', '证件类型')->using(RealnameAuth::getTypes());
            $grid->column('identity', '证件号码');
            $grid->column('status', '审核状态')
                ->using(RealnameAuth::getStatusLabels())
                ->dot(RealnameAuth::getStatusDots(), 'info');

            $grid->column('updated_at', '提交/处理时间')->sortable();

            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id): Show
    {
        return Show::make($id, RealnameAuth::with(['user']), function (Show $show) {
            $show->row(function (Show\Row $show) {
                $show->width(4)->field('user.username', '用户名');
                $show->width(4)->field('user.mobile', '手机');
                $show->width(4)->field('user.email', '邮箱');
            });
            $show->row(function (Show\Row $show) {
                $show->width(4)->field('real_name', '姓名/企业名称');
                $show->width(4)->field('type', '证件类型')->using(RealnameAuth::getTypes());
                $show->width(4)->field('identity', '证件号');
            });
            $show->row(function (Show\Row $show) {
                $show->width(4)->field('contact_person', '联系人');
                $show->width(4)->field('contact_mobile', '联系手机');
                $show->width(4)->field('contact_email', '联系邮箱');
            });
            $show->row(function (Show\Row $show) {
                $show->width(4)->field('id_card_front', '证件正面照片')->image();
                $show->width(4)->field('id_card_back', '证件背面照片')->image();
                $show->width(4)->field('id_card_in_hand', '手持证件照片')->image();
            });
            $show->row(function (Show\Row $show) {
                $show->width(4)->field('license', '营业执照照片')->image();
                $show->width(4)->field('submitted_at', '提交时间');
                $show->width(4)->field('verified_at', '认证通过时间');
            });


            $show->disableEditButton();
            $show->disableDeleteButton();

            $show->tools(function (Show\Tools $tools) use ($show) {
                if ($show->model()->status == RealnameAuth::STATUS_PENDING) {
                    $tools->prepend(new \App\Admin\Actions\Show\UserIdentityApproved());
                    $tools->prepend(new \App\Admin\Actions\Show\UserIdentityReject());
                }
            });
        });
    }
}
