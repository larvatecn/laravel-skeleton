<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * 更新 Admin 资源
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UpdateAdminAssetCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update admin assets.';

    /**
     * @var string
     */
    protected $dcatAdminAssetPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->dcatAdminAssetPath = public_path('vendor/dcat-admin');
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (File::isDirectory($this->dcatAdminAssetPath)) {
            File::deleteDirectory($this->dcatAdminAssetPath);
        }
        $this->call('admin:publish');
    }
}
