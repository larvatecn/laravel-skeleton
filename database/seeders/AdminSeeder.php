<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Database\Seeders;

use App\Models\Administrator;
use Dcat\Admin\Models\Menu;
use Dcat\Admin\Models\Permission;
use Dcat\Admin\Models\Role;
use Illuminate\Database\Seeder;

/**
 * Class AdminSeeder
 * @author Tongle Xu <xutongle@gmail.com>
 */
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = date('Y-m-d H:i:s');

        // create a user.
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
            'name' => 'Administrator',
            'created_at' => $createdAt,
        ]);

        // create a role.
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => Role::ADMINISTRATOR,
            'created_at' => $createdAt,
        ]);

        // add role to user.
        Administrator::first()->roles()->save(Role::first());

        //create a permission
        Permission::truncate();

        //认证管理
        $this->addPermission([
            'name' => '认证管理',
            'slug' => 'auth-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => '网站设置',
                'slug' => 'settings',
                'http_method' => '',
                'http_path' => '/settings',
                'created_at' => $createdAt,
            ],
            [
                'name' => '管理员管理',
                'slug' => 'users',
                'http_method' => '',
                'http_path' => '/auth/users*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '角色管理',
                'slug' => 'roles',
                'http_method' => '',
                'http_path' => '/auth/roles*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '权限管理',
                'slug' => 'permissions',
                'http_method' => '',
                'http_path' => '/auth/permissions*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '菜单管理',
                'slug' => 'menu',
                'http_method' => '',
                'http_path' => '/auth/menu*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '扩展管理',
                'slug' => 'extension',
                'http_method' => '',
                'http_path' => '/auth/extensions*',
                'created_at' => $createdAt,
            ],
        ]);
        //数据管理
        $this->addPermission([
            'name' => '数据管理',
            'slug' => 'dictionary-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => '栏目管理',
                'slug' => 'content-categories-management',
                'http_method' => '',
                'http_path' => '/dictionary/categories*',
                'created_at' => $createdAt
            ],
            [
                'name' => '敏感词管理',
                'slug' => 'stop-word-management',
                'http_method' => '',
                'http_path' => '/dictionary/stop-words*',
                'created_at' => $createdAt
            ],
            [
                'name' => '地区管理',
                'slug' => 'region-management',
                'http_method' => '',
                'http_path' => '/dictionary/region*',
                'created_at' => $createdAt
            ],
            [
                'name' => '邮件验证码',
                'slug' => 'mail-code-management',
                'http_method' => '',
                'http_path' => '/dictionary/mail-codes*',
                'created_at' => $createdAt
            ],
            [
                'name' => '短信验证码',
                'slug' => 'mobile-code-management',
                'http_method' => '',
                'http_path' => '/dictionary/mobile-codes*',
                'created_at' => $createdAt
            ],
            [
                'name' => 'IPV4管理',
                'slug' => 'ipv4-management',
                'http_method' => '',
                'http_path' => '/dictionary/ipv4*',
                'created_at' => $createdAt
            ],
            [
                'name' => '标签管理',
                'slug' => 'content-tags',
                'http_method' => '',
                'http_path' => '/dictionary/tags*',
                'created_at' => $createdAt
            ],
        ]);

        //用户管理
        $this->addPermission([
            'name' => '用户管理',
            'slug' => 'user-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => 'OAuth客户端管理',
                'slug' => 'oauth-management',
                'http_method' => '',
                'http_path' => '/user/clients*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '用户管理管理',
                'slug' => 'user-members',
                'http_method' => '',
                'http_path' => '/user/members*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '社交账户管理',
                'slug' => 'user-socials',
                'http_method' => '',
                'http_path' => '/user/socials*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '实名认证',
                'slug' => 'user-identities',
                'http_method' => '',
                'http_path' => '/user/realname-auth*',
                'created_at' => $createdAt,
            ],
            [
                'name' => '积分记录',
                'slug' => 'user-scores',
                'http_method' => '',
                'http_path' => '/user/scores*',
                'created_at' => $createdAt,
            ],
        ]);

        //内容管理
        $this->addPermission([
            'order' => 5,
            'name' => '内容管理',
            'slug' => 'content-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => '页面管理',
                'slug' => 'content-pages-management',
                'http_method' => '',
                'http_path' => '/content/pages*',
                'created_at' => $createdAt
            ],
            [
                'name' => '文章管理',
                'slug' => 'content-articles-management',
                'http_method' => '',
                'http_path' => '/content/articles*',
                'created_at' => $createdAt
            ],
            [
                'name' => '下载管理',
                'slug' => 'content-downloads-management',
                'http_method' => '',
                'http_path' => '/content/downloads*',
                'created_at' => $createdAt
            ],
            [
                'name' => '评论管理',
                'slug' => 'content-comment-management',
                'http_method' => '',
                'http_path' => '/content/comments*',
                'created_at' => $createdAt
            ],
        ]);

        //模块管理
        $this->addPermission([
            'name' => '模块管理',
            'slug' => 'module-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => '友情链接',
                'slug' => 'module-friendship-links-management',
                'http_method' => '',
                'http_path' => '/module/friendship-links*',
                'created_at' => $createdAt
            ],
            [
                'name' => '百度推送',
                'slug' => 'module-baidu-push-management',
                'http_method' => '',
                'http_path' => '/module/baidu-push*',
                'created_at' => $createdAt
            ],
            [
                'name' => '必应推送',
                'slug' => 'module-bing-push-management',
                'http_method' => '',
                'http_path' => '/module/bing-push*',
                'created_at' => $createdAt
            ],
            [
                'name' => '广告管理',
                'slug' => 'module-advertisements-management',
                'http_method' => '',
                'http_path' => '/module/advertisements*',
                'created_at' => $createdAt
            ],
            [
                'name' => '轮播管理',
                'slug' => 'module-carousels-management',
                'http_method' => '',
                'http_path' => '/module/carousels*',
                'created_at' => $createdAt
            ],
            [
                'name' => '死链管理',
                'slug' => 'module-silian-management',
                'http_method' => '',
                'http_path' => '/module/silian*',
                'created_at' => $createdAt
            ],
        ]);

        //财务管理
        $this->addPermission([
            'name' => '财务管理',
            'slug' => 'transaction-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => '付款单',
                'slug' => 'module-transaction-charges',
                'http_method' => '',
                'http_path' => '/transaction/charges*',
                'created_at' => $createdAt
            ],
            [
                'name' => '退款单',
                'slug' => 'module-transaction-refunds',
                'http_method' => '',
                'http_path' => '/transaction/refunds*',
                'created_at' => $createdAt
            ],
            [
                'name' => '提现管理',
                'slug' => 'module-transaction-transfers',
                'http_method' => '',
                'http_path' => '/transaction/transfers*',
                'created_at' => $createdAt
            ],
        ]);

        //钱包管理
        $this->addPermission([
            'name' => '钱包管理',
            'slug' => 'wallet-management',
            'http_method' => '',
            'http_path' => '',
            'parent_id' => 0,
            'created_at' => $createdAt
        ], [
            [
                'name' => '钱包充值',
                'slug' => 'module-wallet-recharges',
                'http_method' => '',
                'http_path' => '/wallet/recharges*',
                'created_at' => $createdAt
            ],
            [
                'name' => '钱包提现',
                'slug' => 'module-wallet-withdrawals',
                'http_method' => '',
                'http_path' => '/wallet/withdrawals*',
                'created_at' => $createdAt
            ],
            [
                'name' => '钱包交易',
                'slug' => 'module-wallet-transactions',
                'http_method' => '',
                'http_path' => '/wallet/transactions*',
                'created_at' => $createdAt
            ],
        ]);


//        Role::first()->permissions()->save(Permission::first());

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '控制台',
                'icon' => 'feather icon-bar-chart-2',
                'uri' => '/',
                'created_at' => $createdAt,
            ],//控制台 1
            [
                'parent_id' => 0,
                'order' => 2,
                'title' => '系统设置',
                'icon' => 'feather icon-settings',
                'uri' => '',
                'created_at' => $createdAt,
            ],//系统设置 2
            [
                'parent_id' => 0,
                'order' => 3,
                'title' => '数据管理',
                'icon' => 'fa-archive',
                'uri' => '',
                'created_at' => $createdAt,
            ],//数据管理 3
            [
                'parent_id' => 0,
                'order' => 4,
                'title' => '会员管理',
                'icon' => 'feather icon-users',
                'uri' => '',
                'created_at' => $createdAt,
            ],//会员管理 4
            [
                'parent_id' => 0,
                'order' => 5,
                'title' => '内容管理',
                'icon' => 'fa-archive',
                'uri' => '',
                'created_at' => $createdAt,
            ],//内容管理 5
            [
                'parent_id' => 0,
                'order' => 6,
                'title' => '模块管理',
                'icon' => 'fa-modx',
                'uri' => '',
                'created_at' => $createdAt,
            ],//模块管理 6
            [
                'parent_id' => 0,
                'order' => 7,
                'title' => '财务管理',
                'icon' => 'fa-modx',
                'uri' => '',
                'created_at' => $createdAt,
            ],//财务管理 7
            [
                'parent_id' => 0,
                'order' => 8,
                'title' => '钱包管理',
                'icon' => 'fa-modx',
                'uri' => '',
                'created_at' => $createdAt,
            ],//钱包管理 8

        ]);

        //系统设置 2
        $this->addSubMenu(2, [
            [
                'order' => 1,
                'title' => '网站设置',
                'icon' => '',
                'uri' => 'settings',
                'created_at' => $createdAt,
            ],
            [
                'order' => 2,
                'title' => '管理员管理',
                'icon' => '',
                'uri' => 'auth/users',
                'created_at' => $createdAt,
            ],
            [
                'order' => 3,
                'title' => '角色管理',
                'icon' => '',
                'uri' => 'auth/roles',
                'created_at' => $createdAt,
            ],
            [
                'order' => 4,
                'title' => '权限管理',
                'icon' => '',
                'uri' => 'auth/permissions',
                'created_at' => $createdAt,
            ],
            [
                'order' => 5,
                'title' => '菜单管理',
                'icon' => '',
                'uri' => 'auth/menu',
                'created_at' => $createdAt,
            ],
            [
                'order' => 6,
                'title' => '扩展管理',
                'icon' => '',
                'uri' => 'auth/extensions',
                'created_at' => $createdAt,
            ],
        ]);

        //数据管理 3
        $this->addSubMenu(3, [
            [
                'order' => 0,
                'title' => '栏目管理',
                'icon' => '',
                'uri' => 'dictionary/categories',
                'created_at' => $createdAt,
            ],
            [
                'title' => '标签管理',
                'icon' => '',
                'uri' => 'dictionary/tags',
                'created_at' => $createdAt,
            ],
            [
                'order' => 1,
                'title' => '敏感词管理',
                'icon' => '',
                'uri' => 'dictionary/stop-words',
                'created_at' => $createdAt,
            ],
            [
                'order' => 2,
                'title' => '地区管理',
                'icon' => '',
                'uri' => 'dictionary/region',
                'created_at' => $createdAt,
            ],
            [
                'order' => 3,
                'title' => '邮件验证码',
                'icon' => '',
                'uri' => 'dictionary/mail-codes',
                'created_at' => $createdAt,
            ],
            [
                'order' => 4,
                'title' => '短信验证码',
                'icon' => '',
                'uri' => 'dictionary/mobile-codes',
                'created_at' => $createdAt,
            ],
            [
                'order' => 5,
                'title' => 'IPv4管理',
                'icon' => '',
                'uri' => 'dictionary/ipv4',
                'created_at' => $createdAt,
            ],
        ]);

        //会员管理 4
        $this->addSubMenu(4, [
            [
                'order' => 1,
                'title' => 'Auth客户端',
                'icon' => '',
                'uri' => 'user/clients',
                'created_at' => $createdAt,
            ],
            [
                'order' => 2,
                'title' => '会员管理',
                'icon' => '',
                'uri' => 'user/members',
                'created_at' => $createdAt,
            ],
            [
                'order' => 3,
                'title' => '实名认证',
                'icon' => '',
                'uri' => 'user/realname-auth',
                'created_at' => $createdAt,
            ],
            [
                'order' => 4,
                'title' => '社交用户管理',
                'icon' => '',
                'uri' => 'user/socials',
                'created_at' => $createdAt,
            ],
            [
                'order' => 5,
                'title' => '积分记录',
                'icon' => '',
                'uri' => 'user/scores',
                'created_at' => $createdAt,
            ],
        ]);

        //内容管理 5
        $this->addSubMenu(5, [

            [
                'title' => '评论管理',
                'icon' => '',
                'uri' => 'content/comments',
                'created_at' => $createdAt,
            ],

            [
                'title' => '页面管理',
                'icon' => '',
                'uri' => 'content/pages',
                'created_at' => $createdAt,
            ],
            [
                'title' => '文章管理',
                'icon' => '',
                'uri' => 'content/articles',
                'created_at' => $createdAt,
            ],
            [
                'title' => '下载管理',
                'icon' => '',
                'uri' => 'content/downloads',
                'created_at' => $createdAt,
            ],
        ]);

        //模块管理 6
        $this->addSubMenu(6, [
            [
                'title' => '友情链接',
                'icon' => '',
                'uri' => 'module/friendship-links',
                'created_at' => $createdAt,
            ],
            [
                'title' => '百度推送',
                'icon' => '',
                'uri' => 'module/baidu-push',
                'created_at' => $createdAt,
            ],
            [
                'title' => '必应推送',
                'icon' => '',
                'uri' => 'module/bing-push',
                'created_at' => $createdAt,
            ],
            [
                'title' => '广告管理',
                'icon' => '',
                'uri' => 'module/advertisements',
                'created_at' => $createdAt
            ],
            [
                'title' => '轮播管理',
                'icon' => '',
                'uri' => 'module/carousels',
                'created_at' => $createdAt
            ],
            [
                'title' => '死链管理',
                'icon' => '',
                'uri' => 'module/silian',
                'created_at' => $createdAt
            ],
        ]);

        //财务管理 7
        $this->addSubMenu(7, [
            [
                'title' => '付款单',
                'icon' => '',
                'uri' => 'transaction/charges',
                'created_at' => $createdAt
            ],
            [
                'title' => '退款单',
                'icon' => '',
                'uri' => 'transaction/refunds',
                'created_at' => $createdAt
            ],
            [
                'title' => '提现管理',
                'icon' => '',
                'uri' => 'transaction/transfers',
                'created_at' => $createdAt
            ],
        ]);

        //钱包管理 8
        $this->addSubMenu(8, [
            [
                'order' => 1,
                'title' => '钱包充值',
                'icon' => '',
                'uri' => 'wallet/recharges',
                'created_at' => $createdAt
            ],

            [
                'order' => 2,
                'title' => '钱包提现',
                'icon' => '',
                'uri' => 'wallet/withdrawals',
                'created_at' => $createdAt
            ],
            [
                'order' => 3,
                'title' => '钱包流水',
                'icon' => '',
                'uri' => 'wallet/transactions',
                'created_at' => $createdAt
            ],
        ]);


        (new Menu())->flushCache();
    }

    /**
     * 添加权限
     * @param array $parenPermission 父权限
     * @param array $permissions 子权限
     */
    public function addPermission($parenPermission = [], $permissions = []): void
    {
        $createdAt = date('Y-m-d H:i:s');
        $parenPermission['created_at'] = $createdAt;
        $permissionId = Permission::insertGetId($parenPermission);
        $i = 1;
        foreach ($permissions as $permission) {
            $permission['parent_id'] = $permissionId;
            $permission['order'] = $i;
            $permission['created_at'] = $createdAt;
            Permission::insert($permission);
            $i++;
        }
    }

    /**
     * 添加子菜单
     * @param int $parentId 父菜单ID
     * @param array $menus 子菜单
     */
    public function addSubMenu(int $parentId, array $menus = []): void
    {
        $createdAt = date('Y-m-d H:i:s');
        $i = 1;
        foreach ($menus as $menu) {
            $menu['parent_id'] = $parentId;
            $menu['order'] = $i;
            $menu['created_at'] = $createdAt;
            if (isset($menu['submenu'])) {
                $submenu = $menu['submenu'];
                unset($menu['submenu']);
            }
            $menu = Menu::create($menu);

            if (isset($submenu)) {
                $this->addSubMenu($menu->id, $submenu);
                unset($submenu);
            }
            $i++;
        }
    }
}
