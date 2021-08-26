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
use App\Models\Category;
use App\Services\FileService;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;

/**
 * 栏目管理
 * @author Tongle Xu <xutongle@gmail.com>
 */
class CategoryController extends AdminController
{
    /**
     * Get content title.
     *
     * @return string
     */
    public function title(): string
    {
        return '栏目';
    }

    /**
     * 列表
     * @return Grid
     */
    protected function grid(): Grid
    {
        return Grid::make(new Category(), function (Grid $grid) {
            $grid->filter(function (Grid\Filter $filter) {
                $filter->scope('trashed', '回收站')->onlyTrashed();
            });
            $grid->selector(function (Grid\Tools\Selector $selector) {
                $selector->select('type', '类型', Category::getTypeMaps());
            });
            $grid->column('id', 'ID')->bold()->sortable();
            $grid->column('type', '栏目类型')->using(Category::getTypeMaps());
            $grid->column('name', '栏目名称')->tree(); // 开启树状表格功能
            $grid->column('slug', '栏目标识');
            $grid->column('order', '排序')->orderable();
            $grid->column('created_at', '创建时间')->sortable();
            $grid->column('updated_at')->sortable();
            $grid->filter(function (Grid\Filter $filter) {
                $filter->like('title');
                $filter->like('description');
            });
            $grid->quickSearch(['id', 'name']);
            $grid->enableDialogCreate();
            $grid->disableViewButton();

            // 回收站
            if (request('_scope_') == 'trashed') {
                $grid->tools(function (Grid\Tools $tools) {
                    $tools->append(new ForceDelete(Category::class));
                });
                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->append(new Restore(Category::class));
                });
                $grid->batchActions(function (Grid\Tools\BatchActions $batch) {
                    $batch->add(new BatchRestore(Category::class));
                });
            }
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form(): Form
    {
        return Form::make(new Category(), function (Form $form) {
            $form->tools(function (Form\Tools $tools) {
                $tools->disableView();
            });
            $form->display('id', 'ID');
            $form->select('type', '栏目类型')->required()->options(Category::getTypeMaps())->load('parent_id', '/api/categories');
            $form->select('parent_id', '父栏目')->default(0);
            $form->text('name', '栏目名称')->required()->rules('string')->placeholder('请输入栏目名称。');
            $form->text('slug', '栏目标识')->rules('nullable|string')->placeholder('请输入栏目标识。');
            $form->image('thumb_path', '栏目图片')->rules('file|image')->dir(FileService::getImageUploadDirectory())->uniqueName()->autoUpload();
            $form->fieldset('Metas', function (Form $form) {
                $form->text('title', 'Meta Title');
                $form->text('keywords', 'Meta Keywords');
                $form->textarea('description', 'Meta Description');
            })->collapsed();
            $form->display('created_at', trans('admin.created_at'));
            $form->display('updated_at', trans('admin.updated_at'));
        });
    }
}
