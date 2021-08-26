<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Controllers\Dictionary;

use App\Admin\Actions\Grid\BatchRestore;
use App\Admin\Actions\Grid\ForceDelete;
use App\Admin\Actions\Grid\Restore;
use App\Models\Tag;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;

/**
 * 标签管理
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TagController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    protected function title(): string
    {
        return '标签';
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new Tag(), function (Grid $grid) {
            $grid->filter(function (Grid\Filter $filter) {
                //右侧搜索
                $filter->equal('id');
                $filter->equal('name');
                $filter->equal('title', 'SEO标题');
                $filter->scope('trashed', '回收站')->onlyTrashed();
            });
            $grid->quickSearch(['id', 'name','title']);
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id', 'ID')->sortable();
            $grid->column('name', '名称')->display(function ($name) {
                return "<a href='$this->url' target='_blank'>{$name}</a>";
            });
            $grid->column('frequency', '标签热度')->sortable();
            $grid->column('title', 'SEO标题');
            $grid->column('keywords', 'SEO关键词');
            $grid->column('created_at', '创建时间')->sortable();
            //$grid->disableRowSelector();
            $grid->disableViewButton();
            $grid->enableDialogCreate();
            $grid->paginate(10);
            // 回收站
            if (request('_scope_') == 'trashed') {
                $grid->tools(function (Grid\Tools $tools) {
                    $tools->append(new ForceDelete(Tag::class));
                });
                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->append(new Restore(Tag::class));
                });
                $grid->batchActions(function (Grid\Tools\BatchActions $batch) {
                    $batch->add(new BatchRestore(Tag::class));
                });
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form(): Form
    {
        return Form::make(new Tag(), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
            $form->text('name', '名称')->rules('required');
            $form->text('frequency', '标签热度')->default(0);
            $form->fieldset('Metas', function (Form $form) {
                $form->text('title', 'Meta Title');
                $form->text('keywords', 'Meta Keywords');
                $form->textarea('description', 'Meta Description');
            });
        });
    }
}
