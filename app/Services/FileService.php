<?php
/**
 * This is NOT a freeware, use is subject to license terms.
 *
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */
namespace App\Services;

use App\Models\User;
use Closure;
use DateTimeInterface;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\Filesystem\Cloud as CloudFilesystemContract;
use Illuminate\Support\Str;
use League\Flysystem\Config;
use League\Flysystem\FileNotFoundException;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Larva\Support\FileHelper;
use Larva\Support\HtmlHelper;

/**
 * 文件服务
 * @author Tongle Xu <xutongle@gmail.com>
 */
class FileService
{
    public const NAME_UNIQUE = 'unique';
    public const NAME_DATETIME = 'datetime';
    public const NAME_SEQUENCE = 'sequence';
    public const NAME_MD5 = 'md5';
    public const NAME_HASH = 'hash';

    public const DIRECTORY_FILE = 'files';
    public const DIRECTORY_IMAGE = 'images';
    public const DIRECTORY_IDCARD = 'idcard';
    public const DIRECTORY_AVATAR = 'avatar';

    /**
     * Upload directory.
     *
     * @var string
     */
    protected string $directory = '';

    /**
     * File name.
     *
     * @var string|callable|null
     */
    protected $name = null;

    /**
     * Storage instance.
     *
     * @var Filesystem|\League\Flysystem\Filesystem
     */
    protected $storage;

    /**
     * Use (unique or datetime or sequence) name for store upload file.
     *
     * @var bool
     */
    protected $generateName = null;

    /**
     * Controls the storage permission. Could be 'private' or 'public'.
     *
     * @var string
     */
    protected string $visibility;

    /**
     * FileService constructor.
     * @param string|null $disk
     */
    public function __construct(string $disk = null)
    {
        $disk = $disk ?? config('filesystems.default');
        $this->disk($disk);
    }

    /**
     * Initialize the storage instance.
     *
     * @param string|null $disk
     * @return FileService.
     */
    public static function make(string $disk = null): FileService
    {
        return (new static($disk));
    }

    /**
     * 获取图片上传目录
     * @param string|null $disk
     * @return string
     */
    public static function getImageUploadDirectory(string $disk = null): string
    {
        return static::make($disk)->dir(static::DIRECTORY_IMAGE)->getDirectory();
    }

    /**
     * 获取文件上传目录
     * @param string|null $disk
     * @return string
     */
    public static function getFileUploadDirectory(string $disk = null): string
    {
        return static::make($disk)->dir(static::DIRECTORY_FILE)->getDirectory();
    }

    /**
     * Set disk for storage.
     *
     * @param string $disk Disks defined in `config/filesystems.php`.
     * @return $this|bool
     */
    public function disk(string $disk)
    {
        try {
            $this->storage = Storage::disk($disk);
        } catch (Exception $exception) {
            return false;
        }
        return $this;
    }

    /**
     * 获取存储设置
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->storage->getConfig();
    }

    /**
     * Default directory for file to upload.
     *
     * @return string
     */
    public function defaultDirectory(): string
    {
        return static::DIRECTORY_FILE;
    }

    /**
     * 设置上传文件跟目录
     *
     * @param string $dir
     * @return $this
     */
    public function dir(string $dir): FileService
    {
        if ($dir) {
            $this->directory = $dir;
        }
        return $this;
    }

    /**
     * 设置文件名称
     *
     * @param string|callable $name
     * @return $this
     */
    public function name($name): FileService
    {
        if ($name) {
            $this->name = $name;
        }
        return $this;
    }

    /**
     * Use unique name for store upload file.
     *
     * @return $this
     */
    public function uniqueName(): FileService
    {
        $this->generateName = static::NAME_UNIQUE;
        return $this;
    }

    /**
     * Use datetime name for store upload file.
     *
     * @return $this
     */
    public function datetimeName(): FileService
    {
        $this->generateName = static::NAME_DATETIME;
        return $this;
    }

    /**
     * Use sequence name for store upload file.
     *
     * @return $this
     */
    public function sequenceName(): FileService
    {
        $this->generateName = static::NAME_SEQUENCE;
        return $this;
    }

    /**
     * Use md5 name for store upload file.
     *
     * @return $this
     */
    public function md5Name(): FileService
    {
        $this->generateName = static::NAME_MD5;
        return $this;
    }

    /**
     * Use hash name for store upload file.
     *
     * @return $this
     */
    public function hashName(): FileService
    {
        $this->generateName = static::NAME_HASH;
        return $this;
    }

    /**
     * Get getStorage.
     *
     * @return Filesystem
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 获取文件存储目录
     *
     * @return string
     */
    public function getDirectory(): string
    {
        if ($this->directory instanceof Closure) {
            return call_user_func($this->directory);
        }
        return ($this->directory ?: $this->defaultDirectory()) . DIRECTORY_SEPARATOR . date('Y/m');
    }

    /**
     * 获取头像路径
     * @param int|string $userId 用户ID
     * @param string $prefix
     * @return string
     */
    public function getAvatarDirectory($userId, string $prefix = 'avatar'): string
    {
        $id = sprintf('%09d', $userId);
        $dir1 = substr($id, 0, 3);
        $dir2 = substr($id, 3, 2);
        $dir3 = substr($id, 5, 2);
        return ($prefix ? $prefix . '/' : '') . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($userId, -2);
    }

    /**
     * Get file visit url.
     *
     * @param string $path
     * @return string
     */
    public function url(string $path): string
    {
        if (URL::isValidUrl($path)) {
            return $path;
        }
        return $this->storage->url($path);
    }

    /**
     * 获取临时下载地址
     * @param string $file
     * @param DateTimeInterface $expiration 链接有效期
     * @return string
     */
    public function temporaryUrl(string $file, DateTimeInterface $expiration): string
    {
        if ($this->storage instanceof CloudFilesystemContract) {
            try {
                return $this->storage->temporaryUrl($file, $expiration);
            } catch (RuntimeException $exception) {
                Log::error($exception->getMessage());
            }
        }
        return $this->url($file);
    }

    /**
     * 销毁原始文件
     *
     * @param string|null $path
     * @return void.
     */
    public function destroy(string $path = null)
    {
        if (!$path) {
            return;
        }
        if (URL::isValidUrl($path)) {
            $path = parse_url($path, PHP_URL_PATH);
        }
        if (!empty($path) && $this->storage->exists($path)) {
            try {
                $this->storage->delete($path);
            } catch (FileNotFoundException $e) {
            }
        }
    }

    /**
     * Set file permission when stored into storage.
     *
     * @param string $visibility
     * @return $this
     */
    public function visibility(string $visibility): FileService
    {
        $this->visibility = $visibility;
        return $this;
    }

    /**
     * 获取文件存储名称
     *
     * @param File $file
     * @return string
     */
    public function getStoreName(File $file): ?string
    {
        if ($this->generateName == static::NAME_UNIQUE) {
            return $this->generateUniqueName($file);
        } elseif ($this->generateName == static::NAME_DATETIME) {
            return $this->generateDatetimeName($file);
        } elseif ($this->generateName == static::NAME_SEQUENCE) {
            return $this->generateSequenceName($file);
        } elseif ($this->generateName == static::NAME_MD5) {
            return $this->generateMd5Name($file);
        } elseif ($this->generateName == static::NAME_HASH) {
            return $this->generateHashName($file);
        }

        if ($this->name instanceof Closure) {
            return call_user_func_array($this->name, [$this, $file]);
        }

        if (is_string($this->name)) {
            return $this->name;
        }

        return $this->generateClientName($file);
    }

    /**
     * Upload file and delete original file.
     *
     * @param UploadedFile $file
     * @return mixed
     */
    public function upload(UploadedFile $file)
    {
        $this->name = $this->getStoreName($file);
        $this->renameIfExists($file);
        if (!is_null($this->visibility)) {
            return $this->storage->putFileAs($this->getDirectory(), $file, $this->name, $this->visibility);
        }
        return $this->storage->putFileAs($this->getDirectory(), $file, $this->name);
    }

    /**
     * 保存本地文件
     * @param string $file
     * @return false|string
     */
    public function store(string $file)
    {
        $file = new \Illuminate\Http\File($file);
        $this->name = $this->getStoreName($file);
        if (!is_null($this->visibility)) {
            return $this->storage->putFileAs($this->getDirectory(), $file, $this->name, $this->visibility);
        }
        return $this->storage->putFileAs($this->getDirectory(), $file, $this->name);
    }

    /**
     * 保存远程文件到本地
     * @param string $url
     * @return string 本地访问Url
     */
    public function storeRemoteFile(string $url): string
    {
        $fileContent = $this->getRemoteFileContent($url);
        $tempName = storage_path('tmp/' . md5(uniqid() . microtime()) . '.' . FileHelper::getStreamExtension($fileContent));
        FileHelper::put($tempName, $fileContent);
        if (($result = $this->store($tempName)) != false) {
            FileHelper::delete($tempName);
        }
        return $result;
    }

    /**
     * 处理上传头像
     * @param User $user
     * @param UploadedFile|\Illuminate\Http\File $file
     * @return string|false
     */
    public function uploadAvatar(User $user, $file)
    {
        $this->destroy($user->avatar_path);//删除旧头像
        $this->name = $user->id . '.' . $file->getExtension();
        $result = $this->storage->putFileAs($this->getAvatarDirectory($user->id, static::DIRECTORY_AVATAR), $file, $this->name, Filesystem::VISIBILITY_PUBLIC);
        if ($result) {
            $user->setAvatar($result);
            return $result;
        }
        return false;
    }

    /**
     * 证件照片上传
     * @param User $user
     * @param UploadedFile $file
     * @param string $suffix
     * @return false|string
     */
    public function uploadIdCard(User $user, UploadedFile $file, string $suffix = '')
    {
        $this->name = $user->id . $suffix . '.' . $file->getClientOriginalExtension();
        $result = $this->storage->putFileAs($this->getAvatarDirectory($user->id, static::DIRECTORY_IDCARD), $file, $this->name, Filesystem::VISIBILITY_PUBLIC);
        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * 下载内容的远程图片到本地
     * @param string $content
     * @return string
     */
    public function handleContentRemoteFile(string $content): string
    {
        $images = HtmlHelper::getImages($content);
        if (!$images) {
            return $content;
        }
        $this->dir(static::DIRECTORY_IMAGE);
        $remoteFiles = [];
        $localFiles = [];
        foreach ($images as $image) {
            if (URL::isValidUrl($image) && Str::contains($image, $this->getConfig()->get('url')) === false) {
                $remoteFiles[] = $image;
                $localFiles[] = $this->url($this->storeRemoteFile($image));
            }
        }
        return str_replace($remoteFiles, $localFiles, $content);
    }

    /**
     * 获取内容中的本地媒体列表
     * @param string $content
     * @return array
     */
    public function getLocalFilesByContent(string $content): array
    {
        $images = HtmlHelper::getImages($content);
        $files = [];
        if (!$images) {
            return $files;
        }
        $localUrl = $this->getConfig()->get('url');
        foreach ($images as $image) {
            if (Str::contains($image, $localUrl) !== false) {
                $files[] = $image;
            }
        }
        return $files;
    }

    /**
     * 读取远程文件内容
     * @param string $url
     * @return string
     */
    public function getRemoteFileContent(string $url): string
    {
        return Http::retry(2, 2)
            ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.125 Safari/537.36 Edg/84.0.522.59')
            ->get($url)->body();
    }

    /**
     * 从 Html 内容销毁文件
     * @param string $content
     */
    public function destroyFilesByContent(string $content)
    {
        $files = $this->getLocalFilesByContent($content);
        foreach ($files as $file) {
            $this->destroy($file);
        }
    }

    /**
     * If name already exists, rename it.
     *
     * @param File $file
     * @return void
     */
    public function renameIfExists(File $file)
    {
        if ($this->storage->exists("{$this->getDirectory()}/$this->name")) {
            $this->name = $this->generateUniqueName($file);
        }
    }

    /**
     * Generate a unique name for uploaded file.
     *
     * @param File $file
     * @return string
     */
    public function generateUniqueName(File $file): string
    {
        return md5(uniqid() . microtime()) . '.' . $this->getClientOriginalExtension($file);
    }

    /**
     * Generate a datetime name for uploaded file.
     *
     * @param File $file
     * @return string
     */
    public function generateDatetimeName(File $file): string
    {
        return date('YmdHis') . mt_rand(10000, 99999) . '.' . $this->getClientOriginalExtension($file);
    }

    /**
     * Generate a md5 name for uploaded file.
     *
     * @param File $file
     * @return string
     */
    public function generateMd5Name(File $file): string
    {
        return md5_file($file->getPathname()) . '.' . $this->getClientOriginalExtension($file);
    }

    /**
     * Generate a hash name for uploaded file.
     *
     * @param File $file
     * @return string
     */
    public function generateHashName(File $file): string
    {
        return sha1_file($file->getPathname()) . '.' . $this->getClientOriginalExtension($file);
    }

    /**
     * Generate a sequence name for uploaded file.
     *
     * @param File $file
     * @return string
     */
    public function generateSequenceName(File $file): string
    {
        $index = 1;
        $original = $this->generateClientName($file);
        $extension = $this->getClientOriginalExtension($file);
        $new = sprintf('%s_%s.%s', $original, $index, $extension);
        while ($this->storage->exists("{$this->getDirectory()}/$new")) {
            $index++;
            $new = sprintf('%s_%s.%s', $original, $index, $extension);
        }
        return $new;
    }

    /**
     * Use file'oldname for uploaded file.
     *
     * @param File $file
     * @return string
     */
    public function generateClientName(File $file): string
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalName();
        }
        return $file->getFilename();
    }

    /**
     * 获取文件后缀
     * @param File $file
     * @return string
     */
    protected function getClientOriginalExtension(File $file): string
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalExtension();
        } else {
            return $file->getExtension();
        }
    }
}
