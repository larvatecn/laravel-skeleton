<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\Module;

use App\Models\FriendshipLink;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;

/**
 * 友情链接
 * @author Tongle Xu <xutongle@gmail.com>
 */
class FriendshipLinkController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return '友情链接';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new FriendshipLink(), function (Grid $grid) {
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('type', '类型', FriendshipLink::getTypeLabels());
            });

            $grid->filter(function (Grid\Filter $filter) {
                //右侧搜索
                $filter->equal('id', 'ID');
                $filter->like('title', '名称');
                $filter->like('url', 'Url');
                //顶部筛选
                $filter->scope('expired', '已经到期')->where('expired_at', '>', now());
            });
            $grid->quickSearch(['id', 'title']);
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id', 'ID')->sortable();
            $grid->column('type', '链接类型')->using(FriendshipLink::getTypeLabels());
            $grid->column('title', '链接名称');
            $grid->column('url')->link();
            $grid->column('logo')->image();
            $grid->column('description', '描述');
            $grid->column('remark', '备注');
            $grid->column('expired_at', '过期时间');
            $grid->column('created_at', '创建时间')->sortable();
            $grid->enableDialogCreate();
            $grid->showQuickEditButton();
            $grid->disableEditButton();
            $grid->disableRowSelector();
            $grid->disableViewButton();
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
        return Show::make($id, FriendshipLink::query(), function (Show $show) {
            $show->field('id', 'ID');
            $show->field('title', '链接名称');
            $show->field('url', 'Url')->link();
            $show->field('logo', '链接Logo')->image();
            $show->field('description', '链接描述');
            $show->field('remark', '备注');
            $show->field('created_at', '创建时间');
            $show->field('updated_at', '更新时间');
            $show->field('expired_at', '过期时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new FriendshipLink(), function (Form $form) {
            $form->radio('type', '链接类型')->options(FriendshipLink::getTypeLabels())->required()->default(FriendshipLink::TYPE_HOME);
            $form->text('title', '链接名称')->required();
            $form->url('url', 'Url')->required();
            $form->text('description', '链接描述');
            $form->text('remark', '备注')->placeholder('来自：XXX');
            $form->datetime('expired_at', '过期时间');
            $form->image('logo_path', '链接Logo')->rules('file|image')->dir('images/' . date('Y/m'))->uniqueName()->autoUpload();
        });
    }
}
