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

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "oss", "cos"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
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
            'throw' => false,
        ],

        'oss' => [
            'driver' => 'oss',
            'access_id' => env('ALIBABA_CLOUD_ACCESS_KEY_ID'),
            'access_key' => env('ALIBABA_CLOUD_ACCESS_KEY_SECRET'),
            'bucket' => env('ALIYUN_OSS_BUCKET', 'yuncms'),
            'endpoint' => env('ALIYUN_OSS_ENDPOINT', 'oss-cn-shenzhen.aliyuncs.com'),
            'url' => env('ALIYUN_OSS_URL', 'https://oss.larva.com.cn'),
            'root' => env('ALIYUN_OSS_PREFIX', ''),
            'security_token' => null,
            'proxy' => null,
            'timeout' => 3600,
            'ssl' => true,
            'throw' => false,
        ],
        'cos' => [
            'driver' => 'cos',
            // 'endpoint' => getenv('COS_ENDPOINT'),//接入点，留空即可
            'region' => env('TENCENT_COS_REGION'),
            'credentials' => [//认证凭证
                'appId' => env('TENCENT_COS_APP_ID'), //就是存储桶的后缀 如 1258464748
                'secretId' => env('TENCENT_COS_SECRET_ID'),
                'secretKey' => env('TENCENT_COS_SECRET_KEY'),
                'token' => env('TENCENT_COS_TOKEN'),
            ],
            'bucket' => env('TENCENT_COS_BUCKET'),
            'schema' => 'https',
            'timeout' => 3600,
            'connect_timeout' => 3600,
            'root' => env('TENCENT_COS_PREFIX'), //前缀
            'url' => env('TENCENT_COS_URL'), //访问Url
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
