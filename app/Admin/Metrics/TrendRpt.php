<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Metrics;

use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Larva\Support\HttpClient;

/**
 * 趋势报告
 * @author Tongle Xu <xutongle@gmail.com>
 */
class TrendRpt extends Line
{
    /**
     * 图表默认高度.
     *
     * @var int
     */
    protected $chartHeight = 170;

    /**
     * 图表默认配置.
     *
     * @var array
     */
    protected $chartOptions = [
        'chart' => [
            'type' => 'area',
            'toolbar' => [
                'show' => false,
            ],
            'sparkline' => [
                'enabled' => true,
            ],
            'grid' => [
                'show' => false,
                'padding' => [
                    'left' => 0,
                    'right' => 0,
                ],
            ],
        ],
        'tooltip' => [
            'x' => [
                'format' => 'yy/MM/dd',
            ],
        ],
        'xaxis' => [
            'type' => 'date',
            'categories' => [],
        ],
        'yaxis' => [
            'y' => 0,
            'offsetX' => 0,
            'offsetY' => 0,
            'padding' => ['left' => 0, 'right' => 0],
        ],
        'dataLabels' => [
            'enabled' => false,
        ],
        'stroke' => [
            'width' => 2.5,
            'curve' => 'smooth',
        ],
        'fill' => [
            'opacity' => 0.1,
            'type' => 'solid',
        ],
    ];

    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();
        // 标题
        $this->title('访问趋势');
        // 设置下拉选项
        $this->dropdown([
            '7' => '最近7天',
            '28' => '最近28天',
            '90' => '最近90天',
            '365' => '最近1年',
        ]);
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public function handle(Request $request)
    {
        $end = Carbon::today();
        switch ($request->get('option')) {
            case '7':
                $start = Carbon::now()->subDays(7);
                break;
            case '28':
                $start = Carbon::now()->subDays(28);
                break;
            case '90':
                $start = Carbon::now()->subDays(90);
                break;
            case '365':
                $start = Carbon::now()->subDays(365);
                break;
            default:
                $start = Carbon::now()->subDays(7);
        }
        $this->buildData($start, $end);
    }

    /**
     * 获取数据
     * @param Carbon $start
     * @param Carbon $end
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public function buildData($start, $end)
    {
        $response = $this->getData($start, $end);
        if ($response) {
            $title = $response['timeSpan'][0];
            $categories = [];
            foreach ($response['items'][0] as $data) {
                $categories[] = $data[0];
            }
            $pv = [];
            $uv = [];
            $ip = [];
            foreach ($response['items'][1] as $data) {
                $pv[] = $data[0];
                $uv[] = $data[1];
                $ip[] = $data[2];
            }

            $this->chartOption('xaxis', [
                'type' => 'date',
                'categories' => $categories
            ]);

            $color = Admin::color();

            $this->chartColors([
                '#FF7F00', '#7BD39A', $color->primary()
            ]);

            // 卡片内容
            $this->withContent($title);
            // 图表数据
            $this->withChart([
                [
                    'name' => '浏览量(PV)',
                    'data' => $pv,
                ],
                [
                    'name' => '访客数(UV)',
                    'data' => $uv,
                ],
                [
                    'name' => 'IP数',
                    'data' => $ip,
                ],
            ]);
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
            'series' => $data,
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

    /**
     * 获取图标数据
     * @param Carbon $start
     * @param Carbon $end
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Larva\Support\Exception\ConnectionException
     */
    public function getData(Carbon $start, Carbon $end)
    {
        $start = $start->format('Ymd');
        $end = $end->format('Ymd');
        $response = HttpClient::make()->postJSON('https://api.baidu.com/json/tongji/v1/ReportService/getData', [
            'header' => [
                'username' => settings('tongji.baidu_username'),
                'password' => settings('tongji.baidu_password'),
                'token' => settings('tongji.baidu_token'),
                'account_type' => 1
            ],
            'body' => [
                'site_id' => settings('tongji.baidu_siteid'),
                'start_date' => $start,
                'end_date' => $end,
                'metrics' => 'pv_count,visitor_count,ip_count',
                'method' => 'overview/getTimeTrendRpt'
            ]
        ]);
        if ($response['header']['status'] == 0 && $response['body']['data'][0]['result']) {
            return $response['body']['data'][0]['result'];
        }
        return false;
    }
}
