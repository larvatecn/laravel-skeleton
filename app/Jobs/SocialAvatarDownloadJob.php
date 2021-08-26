<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Jobs;

use App\Models\User;
use App\Services\FileService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Larva\Support\FileHelper;

/**
 * 社交账户头像下载
 * @author Tongle Xu <xutongle@gmail.com>
 */
class SocialAvatarDownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 用户ID
     *
     * @var int
     */
    public $userId;

    /**
     * @var string 头像Url
     */
    public $faceUrl;

    /**
     * Create a new job instance.
     *
     * @param int|string $userId
     * @param string $faceUrl
     */
    public function __construct($userId, string $faceUrl)
    {
        $this->userId = (int)$userId;
        $this->faceUrl = $faceUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileManager = FileService::make();
        $user = User::find($this->userId);
        if ($user) {
            // 获取头像内容
            $fileContent = $fileManager->getRemoteFileContent($this->faceUrl);
            $tmpPath = storage_path('tmp/');
            if (!FileHelper::isDirectory($tmpPath)) {
                FileHelper::makeDirectory($tmpPath);
            }
            $tmpFile = $tmpPath . $user->id . '_avatar.' . FileHelper::getStreamExtension($fileContent);
            FileHelper::put($tmpFile, $fileContent);
            $fileManager->uploadAvatar($user, new File($tmpFile));
            @unlink($tmpFile);
        }
    }
}
