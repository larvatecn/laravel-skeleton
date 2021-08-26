<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\User;

use App\Admin\Actions\Grid\BatchRestore;
use App\Admin\Actions\Grid\ForceDelete;
use App\Admin\Actions\Grid\Restore;
use App\Admin\Metrics\NewDevices;
use App\Admin\Metrics\NewSocialUsers;
use App\Admin\Metrics\NewUsers;
use App\Admin\Repositories\User;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Carbon;

/**
 * 会员管理
 * @author Tongle Xu <xutongle@gmail.com>
 */
class MemberController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return '会员';
    }

    public function index(Content $content): Content
    {
        return $content
            ->header('会员')
            //->description('表格功能展示')
            ->body(function (Row $row) {
                $row->column(4, new NewUsers());
                $row->column(4, new NewDevices());
                $row->column(4, new NewSocialUsers());
            })
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new User(), function (Grid $grid) {
            $grid->filter(function (Grid\Filter $filter) {
                //右侧搜索
                $filter->equal('id');
                $filter->equal('mobile');
                $filter->equal('email');
                //顶部筛选
                $filter->scope('today', '今天数据')->whereDay('created_at', Carbon::today());
                $filter->scope('yesterday', '昨天数据')->whereDay('created_at', Carbon::yesterday());
                $thisWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                $filter->scope('this_week', '本周数据')->whereBetween('created_at', $thisWeek);
                $lastWeek = [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()];
                $filter->scope('last_week', '上周数据')->whereBetween('created_at', $lastWeek);
                $filter->scope('this_month', '本月数据')->whereMonth('created_at', Carbon::now()->month);
                $filter->scope('last_month', '上月数据')->whereBetween('created_at', [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->subMonth()->endOfDay()]);
                $filter->scope('year', '本年数据')->whereYear('created_at', Carbon::now()->year);
                $filter->scope('trashed', '回收站')->onlyTrashed();
            });
            $grid->quickSearch(['id', 'mobile', 'email']);
            $grid->model()->with(['profile', 'extra'])->orderBy('id', 'desc');
            $grid->column('id', 'ID')->sortable();
            $grid->column('avatar', '头像')->image('', 50, 50);
            $grid->column('username', '用户名');
            $grid->column('mobile', '手机');
            $grid->column('email', '邮箱');
            $grid->column('score', '积分');
            $grid->column('available_amount', '现金余额')->display(function ($amount) {
                return bcdiv($amount, 100, 2) . '元';
            });
            $grid->column('extra.login_num', '登录次数');
            $grid->column('extra.login_ip', '登录IP');
            $grid->column('extra.login_at', '最后登录');
            $grid->column('status', '状态')->using([
                \App\Models\User::STATUS_NORMAL => '正常',
                \App\Models\User::STATUS_DISABLED => '禁用',
            ]);

            $grid->column('created_at', '注册时间')->sortable();

            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->paginate(10);
            if (request('_scope_') == 'trashed') {// 回收站
                $grid->tools(function (Grid\Tools $tools) {
                    $tools->append(new ForceDelete(User::class));
                });
                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->append(new Restore(User::class));
                });
                $grid->batchActions(function (Grid\Tools\BatchActions $batch) {
                    $batch->add(new BatchRestore(User::class));
                });
            }
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
        return Show::make($id, new User(), function (Show $show) {
            $with = ['profile', 'extra'];
            if (class_exists('\Larva\Wallet\Models\Wallet')) {
                $with[] = 'wallet';
            }

            $show->model()->with($with);

            $show->row(function (Show\Row $show) {
                $show->width(4)->field('id');
                $show->width(4)->field('username');
                $show->width(4)->field('mobile');
            });
            $show->row(function (Show\Row $show) {
                $show->width(4)->field('email');
                $show->width(4)->field('extra.login_num');
                $show->width(4)->field('extra.login_ip');
            });

            $show->row(function (Show\Row $show) {
                $show->width(4)->field('extra.login_at');
                $show->width(4)->field('status', '状态')->using([
                    \App\Models\User::STATUS_NORMAL => '正常',
                    \App\Models\User::STATUS_DISABLED => '禁用',
                ]);
                $show->width(4)->field('mobile_verified_at');
            });

            $show->row(function (Show\Row $show) {
                $show->width(4)->field('email_verified_at');
                $show->width(4)->field('created_at');
                $show->width(4)->field('updated_at');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(User::with('profile'), function (Form $form) {
            $form->tab('基本信息', function (Form $form) {
                $form->display('id');
                $form->text('username');
                $form->text('mobile', '手机');
                $form->text('mobile_verified_at', '手机验证时间');
                $form->email('email');
                $form->text('email_verified_at', '邮箱验证时间');
                $form->radio('status', '状态')->options([
                    \App\Models\User::STATUS_NORMAL => '正常',
                    \App\Models\User::STATUS_DISABLED => '禁用',
                ]);
                $form->display('created_at', '注册时间');
                $form->display('updated_at', '更新时间');
            })->tab('个人信息', function (Form $form) {
                $form->date('profile.birthday', '生日');
                $form->url('profile.address', '联系地址');
                $form->text('profile.website', '个人主页');
                $form->textarea('profile.introduction', '个人描述');
                $form->textarea('profile.bio', '个性签名');

                $form->row(function (Form\Row $form) {
                    $form->width(4)->select('profile.province_id', '省')->options(\App\Models\Region::getProvinceSelect())->load('profile.city_id', '/api/regions');
                    $form->width(4)->select('profile.city_id', '市')->load('profile.district_id', '/api/regions');
                    $form->width(4)->select('profile.district_id', '区');
                });
            });
        });
    }
}
