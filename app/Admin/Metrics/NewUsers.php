<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Metrics;

use App\Models\Statistic;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;

class NewUsers extends Line
{
    /**
     * 初始化卡片内容
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $this->title('新用户');
        $this->dropdown([
            '7' => '最近7天',
            '28' => '最近28天',
            '90' => '最近90天',
            '365' => '最近一年',
        ]);
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        switch ($request->get('option')) {
            case '365':
                $data = Statistic::getTimingHistory(Statistic::TYPE_NEW_USER, 365);
                // 卡片内容
                $this->withContent($data['quantity']);
                // 图表数据
                $this->withChart($data['data']->toArray());
                break;
            case '90':
                $data = Statistic::getTimingHistory(Statistic::TYPE_NEW_USER, 90);
                // 卡片内容
                $this->withContent($data['quantity']);
                // 图表数据
                $this->withChart($data['data']->toArray());
                break;
            case '28':
                $data = Statistic::getTimingHistory(Statistic::TYPE_NEW_USER, 28);
                // 卡片内容
                $this->withContent($data['quantity']);
                // 图表数据
                $this->withChart($data['data']->toArray());
                break;
            case '7':
            default:
                $data = Statistic::getTimingHistory(Statistic::TYPE_NEW_USER, 7);
                // 卡片内容
                $this->withContent($data['quantity']);
                // 图表数据
                $this->withChart($data['data']->toArray());
        }
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => [
                [
                    'name' => $this->title,
                    'data' => $data,
                ],
            ],
        ]);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
    <span class="mb-0 mr-1 text-80">{$this->title}</span>
</div>
HTML
        );
    }
}
