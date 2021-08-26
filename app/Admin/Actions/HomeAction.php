<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Admin\Actions;

use Dcat\Admin\Actions\Action;

/**
 * 点击打开首页
 * @author Tongle Xu <xutongle@gmail.com>
 */
class HomeAction extends Action
{
    /**
     * @return string
     */
    public function render(): string
    {
        $appUrl = config('app.url');
        $host = parse_url($appUrl, PHP_URL_HOST);
        return <<<HTML
<ul class="nav navbar-nav">
    <li class="nav-item"><a class="nav-link" href="{$appUrl}" target="_blank"><i class="fa fa-lg fa-fw fa-home"></i>前台</a></li>
   <li class="nav-item"><a class="nav-link" href="https://www.aizhan.com/cha/{$host}/" target="_blank"><i class="fa fa-lg fa-fw  fa-external-link"></i>爱站SEO</a></li>
   <li class="nav-item"><a class="nav-link" href="http://seo.chinaz.com/{$host}" target="_blank"><i class="fa fa-lg fa-fw  fa-external-link"></i>站长SEO</a></li>
   <li class="nav-item"><a class="nav-link" href="https://seo.5118.com/{$host}" target="_blank"><i class="fa fa-lg fa-fw  fa-external-link"></i>5118</a></li>
</ul>
HTML;
    }
}
