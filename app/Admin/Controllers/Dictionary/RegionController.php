<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\Dictionary;

use App\Models\Region;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;

/**
 * 地区管理
 * @author Tongle Xu <xutongle@gmail.com>
 */
class RegionController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    public function title(): string
    {
        return '地区';
    }

    protected function grid(): Grid
    {
        return Grid::make(new Region(), function (Grid $grid) {
            $grid->column('id', 'ID')->bold()->sortable();
            $grid->column('name', '名称')->tree(); // 开启树状表格功能
            $grid->column('order', '排序')->orderable();
            $grid->column('level', '等级');
            $grid->column('initial', '首字母');
            $grid->column('pinyin', '拼音');
            $grid->column('city_code', '城市编码');
            $grid->column('ad_code', '区域编码');
            $grid->column('lng_lat', '中心经纬度');
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('name');
            });
            $grid->quickSearch(['id', 'name', 'initial', 'pinyin']);
            $grid->enableDialogCreate();
            $grid->showQuickEditButton();
            $grid->disableEditButton();
            $grid->disableViewButton();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form(): Form
    {
        return Form::make(new Region(), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
            $form->display('id', 'ID');
            $form->text('parent_id', '父地区ID')->default(0);
            $form->text('name', '名称')->required()->placeholder('请输入地区名称。');
            $form->number('level', '等级')->required();
            $form->text('initial', '首字母')->required();
            $form->text('pinyin', '拼音')->required();
            $form->text('city_code', '城市编码')->required();
            $form->text('ad_code', '区域编码')->required();
            $form->text('lng_lat', '中心经纬度')->required();
        });
    }
}
