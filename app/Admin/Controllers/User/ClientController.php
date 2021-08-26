<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\User;

use App\Models\PassportClient;
use App\Models\User;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Carbon;

/**
 * OAuth客户端管理
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ClientController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return 'OAuth客户端';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new PassportClient(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->model()->with(['user']);
            $grid->column('id')->sortable();
            $grid->column('user.username');
            $grid->column('name')->editable();
            $grid->column('description');
            $grid->column('secret');
            $grid->column('redirect');
            $grid->column('personal_access_client')->switch();
            $grid->column('password_client')->switch();
            $grid->column('revoked')->switch();
            $grid->column('created_at')->sortable();
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->scope('today', '今天数据')->whereDay('created_at', Carbon::today());
                $filter->scope('yesterday', '昨天数据')->whereDay('created_at', Carbon::yesterday());
                $thisWeek = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
                $filter->scope('this_week', '本周数据')
                    ->whereBetween('created_at', $thisWeek);
                $lastWeek = [Carbon::now()->startOfWeek()->subWeek(), Carbon::now()->endOfWeek()->subWeek()];
                $filter->scope('last_week', '上周数据')->whereBetween('created_at', $lastWeek);
                $filter->scope('this_month', '本月数据')->whereMonth('created_at', Carbon::now()->month);
                $filter->scope('last_month', '上月数据')->whereBetween('created_at', [Carbon::now()->subMonth()->startOfDay(), Carbon::now()->subMonth()->endOfDay()]);
                $filter->scope('year', '本年数据')->whereYear('created_at', Carbon::now()->year);

                $filter->equal('id', '应用ID');
                $filter->equal('user_id', '用户ID');
                $filter->equal('revoked', '吊销')->select([
                    0 => '否',
                    1 => '是',
                ]);
                $filter->equal('personal_access_client', '个人访问令牌')->select([
                    0 => '否',
                    1 => '是',
                ]);
                $filter->equal('password_client', '密码授权')->select([
                    0 => '否',
                    1 => '是',
                ]);
            });

            $grid->enableDialogCreate();
            $grid->disableRowSelector();
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
        return Show::make($id, new PassportClient(), function (Show $show) {
            $show->field('id');
            $show->field('user_id');
            $show->field('name');
            $show->field('description');
            $show->field('website');
            $show->field('logo');
            $show->field('secret');
            $show->field('redirect');
            $show->field('personal_access_client');
            $show->field('password_client');
            $show->field('revoked');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new PassportClient(), function (Form $form) {
            $form->select('user_id')->model(User::class, 'id', 'username')->ajax('api/users', 'id', 'username')->rules('required_if:personal_access_client,1');
            $form->text('name')->required();
            $form->textarea('description', '简介');
            $form->url('website', '主页');
            $form->image('logo_path', 'Logo')->rules('image')->autoUpload();
            $form->text('secret');
            $form->text('redirect')->default('http://localhost');
            $form->switch('personal_access_client');
            $form->switch('password_client');
            $form->switch('revoked');
        });
    }
}
