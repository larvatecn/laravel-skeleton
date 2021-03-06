<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Actions\Grid;

use Dcat\Admin\Grid\BatchAction;
use Illuminate\Http\Request;

/**
 * 批量恢复
 * @author Tongle Xu <xutongle@gmail.com>
 */
class BatchRestore extends BatchAction
{
    protected $title = '<i class="feather icon-rotate-ccw"></i> '.'恢复';

    protected $model;

    /**
     * BatchRestore constructor.
     * @param string|null $model
     */
    public function __construct(string $model = null)
    {
        $this->model = $model;
        parent::__construct($this->title);
    }

    public function handle(Request $request)
    {
        $model = $request->get('model');
        foreach ((array)$this->getKey() as $key) {
            $model::withTrashed()->findOrFail($key)->restore();
        }
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
