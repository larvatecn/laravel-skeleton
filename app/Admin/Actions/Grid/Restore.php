<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Actions\Grid;

use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

/**
 * 行恢复
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Restore extends RowAction
{
    protected $title = '<i class="feather icon-rotate-ccw"></i> '.'恢复';

    /**
     * @var string|null
     */
    protected $model;

    /**
     * Restore constructor.
     * @param string|null $model
     */
    public function __construct(string $model = null)
    {
        $this->model = $model;
        parent::__construct($this->title);
    }

    public function handle(Request $request)
    {
        $key = $this->getKey();
        $model = $request->get('model');

        $model::withTrashed()->findOrFail($key)->restore();

        return $this->response()->success('已恢复')->refresh();
    }

    public function confirm(): array
    {
        return ['确定恢复吗？'];
    }

    public function parameters()
    {
        return [
            'model' => $this->model,
        ];
    }
}
