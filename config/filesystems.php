<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "oss", "cos", "qiniu"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],

        'oss' => [
            'driver' => 'oss',
            'access_id' => env('ALIYUN_ACCESS_ID'),
            'access_key' => env('ALIYUN_ACCESS_KEY'),
            'bucket' => env('OSS_BUCKET', 'baidu'),
            'endpoint' => env('OSS_ENDPOINT', 'oss-cn-shenzhen.aliyuncs.com'),
            'url' => env('OSS_URL', 'https://www.baidu.com'),
            'prefix' => env('OSS_PREFIX', null),
            'security_token' => null,
            'proxy' => null,
            'timeout' => 3600,
            'ssl' => true
        ],

        'cos' => [
            'driver' => 'cos',
            // 'endpoint' => getenv('COS_ENDPOINT'),//接入点，留空即可
            'region' => env('COS_REGION', 'ap-guangzhou'),
            'credentials' => [
                'appId' => env('COS_APP_ID', '123456'),//就是存储桶的后缀 如 1258464748
                'secretId' => env('TENCENT_SECRET_ID'),
                'secretKey' => env('TENCENT_SECRET_KEY'),
                'token' => env('COS_TOKEN'),
            ],
            'bucket' => 'baidu-1304112063',
            'schema' => 'https',
            'prefix' => getenv('COS_PREFIX'),//前缀
            'encrypt' => null,
            'url' => null,
            'cdn_key' => 'abcdefg',
            'cdn_sign_type' => 'D'//A/B/C/D
        ],

        'qiniu' => [
            'driver' => 'qiniu',
            'access_key' => env('QINIU_ACCESS_KEY'),
            'secret_key' => env('QINIU_SECRET_KEY'),
            'bucket' => env('QINIU_BUCKET', 'baidu'),
            'prefix' => env('QINIU_PREFIX'), // optional
            'url' => env('QINIU_BUCKET_URL', 'http://www.baidu.com'),
            'visibility' => 'public',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
